<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryRepository;
    
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Get all categories",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $categories = $this->categoryRepository->paginate($perPage);
        return CategoryResource::collection($categories);
    }
    
    /**
     * @OA\Get(
     *     path="/api/categories/featured",
     *     summary="Get featured categories",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     )
     * )
     */
    public function featured()
    {
        $featuredCategories = $this->categoryRepository->getFeatured();
        return CategoryResource::collection($featuredCategories);
    }
    
    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     summary="Get category by ID",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function show($id)
    {
        $category = $this->categoryRepository->find($id);
        
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        
        return new CategoryResource($category);
    }
    
    /**
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Create a new category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(CategoryRequest $request)
    {
        $category = $this->categoryRepository->create($request->validated());
        return new CategoryResource($category);
    }
    
    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     summary="Update a category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(CategoryRequest $request, $id)
    {
        $updated = $this->categoryRepository->update($id, $request->validated());
        
        if (!$updated) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        
        return new CategoryResource($updated);
    }
    
    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Delete a category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $deleted = $this->categoryRepository->delete($id);
        
        if (!$deleted) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        
        return response()->json(['message' => 'Category deleted successfully']);
    }
}