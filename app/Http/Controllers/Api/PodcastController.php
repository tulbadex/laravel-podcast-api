<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PodcastResource;
use App\Repositories\Interfaces\PodcastRepositoryInterface;
use App\Http\Requests\PodcastRequest;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    protected $podcastRepository;
    
    public function __construct(PodcastRepositoryInterface $podcastRepository)
    {
        $this->podcastRepository = $podcastRepository;
    }
    
    /**
     * @OA\Get(
     *     path="/api/podcasts",
     *     summary="Get all podcasts",
     *     tags={"Podcasts"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         @OA\Schema(type="string", enum={"title", "rating", "created_at"})
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         @OA\Schema(type="string", enum={"asc", "desc"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $sort = $request->query('sort', 'created_at');
        $order = $request->query('order', 'desc');
        $category = $request->query('category');
        $featured = $request->query('featured');
        
        $validSortFields = ['title', 'rating', 'created_at'];
        if (!in_array($sort, $validSortFields)) {
            $sort = 'created_at';
        }

        $query = $this->podcastRepository
            ->with(['category', 'episodes'])
            ->withCount('episodes')
            ->orderBy($sort, $order);

        if ($category) {
            $query = $query->where('category_id', $category);
        }

        if ($featured !== null) {
            $query = $query->where('featured', filter_var($featured, FILTER_VALIDATE_BOOLEAN));
        }

        $podcasts = $query->paginate($perPage);
            
        return PodcastResource::collection($podcasts);
    }
    
    /**
     * @OA\Get(
     *     path="/api/podcasts/featured",
     *     summary="Get featured podcasts",
     *     tags={"Podcasts"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     )
     * )
     */
    public function featured()
    {
        $featuredPodcasts = $this->podcastRepository->getFeatured();
        return PodcastResource::collection($featuredPodcasts);
    }
    
    /**
     * @OA\Get(
     *     path="/api/podcasts/category/{categoryId}",
     *     summary="Get podcasts by category",
     *     tags={"Podcasts"},
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     )
     * )
     */
    public function byCategory($categoryId)
    {
        $podcasts = $this->podcastRepository->getByCategory($categoryId);
        return PodcastResource::collection($podcasts);
    }
    
    /**
     * @OA\Get(
     *     path="/api/podcasts/search",
     *     summary="Search podcasts",
     *     tags={"Podcasts"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     )
     * )
     */
    public function search(Request $request)
    {
        $query = $request->query('q');
        if (!$query) {
            return response()->json(['message' => 'Search query is required'], 400);
        }
        
        $podcasts = $this->podcastRepository->search($query);
        return PodcastResource::collection($podcasts);
    }
    
    /**
     * @OA\Get(
     *     path="/api/podcasts/{id}",
     *     summary="Get podcast by ID",
     *     tags={"Podcasts"},
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
     *         description="Podcast not found"
     *     )
     * )
     */
    public function show($id)
    {
        $podcast = $this->podcastRepository->with(['category', 'episodes'])->find($id);
        
        if (!$podcast) {
            return response()->json(['message' => 'Podcast not found'], 404);
        }
        
        return new PodcastResource($podcast);
    }
    
    /**
     * @OA\Post(
     *     path="/api/podcasts",
     *     summary="Create a new podcast",
     *     tags={"Podcasts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PodcastRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Podcast created successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(PodcastRequest $request)
    {
        $podcast = $this->podcastRepository->create($request->validated());
        return new PodcastResource($podcast);
    }
    
    /**
     * @OA\Put(
     *     path="/api/podcasts/{id}",
     *     summary="Update a podcast",
     *     tags={"Podcasts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PodcastRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Podcast updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Podcast not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(PodcastRequest $request, $id)
    {
        $updated = $this->podcastRepository->update($id, $request->validated());
        
        if (!$updated) {
            return response()->json(['message' => 'Podcast not found'], 404);
        }
        
        return new PodcastResource($updated);
    }
    
    /**
     * @OA\Delete(
     *     path="/api/podcasts/{id}",
     *     summary="Delete a podcast",
     *     tags={"Podcasts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Podcast deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Podcast not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $deleted = $this->podcastRepository->delete($id);
        
        if (!$deleted) {
            return response()->json(['message' => 'Podcast not found'], 404);
        }
        
        return response()->json(['message' => 'Podcast deleted successfully']);
    }
}