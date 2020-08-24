<?php namespace App\Services;
use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Image;
use App\Repositories\BookRepository;
use App\Repositories\ImageRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BookService
{
    protected $bookRepository;
    protected $imageRepository;
    public function __construct(BookRepository $bookRepository,
                                ImageRepository $imageRepository
    )
    {
        $this->bookRepository = $bookRepository;
        $this->imageRepository = $imageRepository;
    }

    /**
     * @param CreateBookRequest $request
     * @return mixed
     */
    public function create(CreateBookRequest $request) {
        DB::beginTransaction();
        // lưu sách vào db
        $book = $this->bookService->create([$request->all()]);

        // lưu hình ảnh
        $url = $this->saveImageAndGetUrl($request->images);

        $image = new Image();
        $image->url = $url;
        $image->book_id = $book->id;

        $this->imageRepository->create([$image]);
        DB::commit();
        return $book;
    }

    /**
     * @param UpdateBookRequest $request
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function update(UpdateBookRequest $request, $id) {
        $book = $this->bookRepository->show($id);
        // authorize
        if (!Gate::allows('update-book', [$book])) {
            throw new \Exception('You are not authorized to edit this book', 555);
        }

        DB::beginTransaction();
        $this->bookRepository->update([$request], $id);
        $this->updateBookImage($request, $book);

        DB::commit();
        return $book;
    }

    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        $book = $this->bookRepository->show($id);
        // Authorize
        if (!Gate::allows('delete-book', [$book])) {
            $this->sendError('You are not authorized to delete this book', null, 500);
        }
        if (count($book->comments) > 0 || count($book->ratings) > 0) {
            $this->sendError('This book cannot be deleted because there are already comments and ratings for it', null, 500);
        }
        $this->bookRepository->delete($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->bookRepository->show($id);
    }

    /**
     * @param UploadedFile $images
     * @return mixed
     */
    public function saveImageAndGetUrl(UploadedFile $images)
    {
        $fileName = time() . '.' . $images->extension();
        Storage::disk('public')->putFileAs('bookcovers', $images, $fileName);
        return Storage::disk('public')->url('bookcovers/' . $fileName);
    }

    /**
     * @param $imageToDelete
     */
    public function deleteImageFromStorage($imageToDelete): void
    {
        if ($imageToDelete !== null) {
            $oldUrl = $imageToDelete->url;
            File::delete($oldUrl);
        }
    }

    public function all()
    {
        return $this->bookRepository->all();
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        return $this->bookRepository->search($query);
    }

    /**
     * @param UpdateBookRequest $request
     * @param $book
     * @return void
     */
    public function updateBookImage(UpdateBookRequest $request, $book)
    {
        // update image if it has been changed
        if ($request->input('images')) {
            // delete old image
            $imageIds = array();
            foreach ($book->images as $image) {
                array_push($imageIds, $image->id);
            }
            $imageToDelete = $this->imageRepository->findByIdIn($imageIds);

            $this->deleteImageFromStorage($imageToDelete);

            $this->imageRepository->delete($imageToDelete->id);

            // lưu hình ảnh
            $url = $this->saveImageAndGetUrl($request->input('images'));

            // save to image table
            $image = new Image();
            $image->url = $url;
            $image->book_id = $book->id;
            $this->imageRepository->create([$image]);
        }
    }
}
