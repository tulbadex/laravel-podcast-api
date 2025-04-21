<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EpisodeResource;
use App\Repositories\Interfaces\EpisodeRepositoryInterface;
use App\Http\Requests\EpisodeRequest;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    protected $episodeRepository;
    
    public function __construct(EpisodeRepositoryInterface $episodeRepository)
    {
        $this->episodeRepository = $episodeRepository;
    }
    
    /**
     * @OA\Get(
     *     path="/api/episodes",
     *     summary="Get all episodes",
     *     tags={"Episodes"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         @OA\Schema(type="string", enum={"title", "published_at"})
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
        $sort = $request->query('sort', 'published_at');
        $order = $request->query('order', 'desc');
        
        $validSortFields = ['title', 'published_at', 'duration_in_seconds'];
        if (!in_array($sort, $validSortFields)) {
            $sort = 'published_at';
        }
        
        $episodes = $this->episodeRepository->with('podcast')
            ->orderBy($sort, $order)
            ->paginate($perPage);
            
        return EpisodeResource::collection($episodes);
    }
    
    /**
     * @OA\Get(
     *     path="/api/episodes/featured",
     *     summary="Get featured episodes",
     *     tags={"Episodes"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     )
     * )
     */
    public function featured()
    {
        $featuredEpisodes = $this->episodeRepository->getFeatured();
        return EpisodeResource::collection($featuredEpisodes);
    }
    
    /**
     * @OA\Get(
     *     path="/api/episodes/latest",
     *     summary="Get latest episodes",
     *     tags={"Episodes"},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     )
     * )
     */
    public function latest(Request $request)
    {
        $limit = $request->query('limit', 10);
        $latestEpisodes = $this->episodeRepository->getLatest($limit);
        return EpisodeResource::collection($latestEpisodes);
    }
    
    /**
     * @OA\Get(
     *     path="/api/episodes/podcast/{podcastId}",
     *     summary="Get episodes by podcast",
     *     tags={"Episodes"},
     *     @OA\Parameter(
     *         name="podcastId",
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
    public function byPodcast($podcastId)
    {
        $episodes = $this->episodeRepository->getByPodcast($podcastId);
        return EpisodeResource::collection($episodes);
    }
    
    /**
     * @OA\Get(
     *     path="/api/episodes/{id}",
     *     summary="Get episode by ID",
     *     tags={"Episodes"},
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
     *         description="Episode not found"
     *     )
     * )
     */
    public function show($id)
    {
        $episode = $this->episodeRepository->with('podcast')->find($id);
        
        if (!$episode) {
            return response()->json(['message' => 'Episode not found'], 404);
        }
        
        return new EpisodeResource($episode);
    }
    
    /**
     * @OA\Post(
     *     path="/api/episodes",
     *     summary="Create a new episode",
     *     tags={"Episodes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EpisodeRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Episode created successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(EpisodeRequest $request)
    {
        $episode = $this->episodeRepository->create($request->validated());
        return new EpisodeResource($episode);
    }
    
    /**
     * @OA\Put(
     *     path="/api/episodes/{id}",
     *     summary="Update an episode",
     *     tags={"Episodes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EpisodeRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Episode updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Episode not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(EpisodeRequest $request, $id)
    {
        $updated = $this->episodeRepository->update($id, $request->validated());
        
        if (!$updated) {
            return response()->json(['message' => 'Episode not found'], 404);
        }
        
        return new EpisodeResource($updated);
    }
    
    /**
     * @OA\Delete(
     *     path="/api/episodes/{id}",
     *     summary="Delete an episode",
     *     tags={"Episodes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Episode deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Episode not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $deleted = $this->episodeRepository->delete($id);
        
        if (!$deleted) {
            return response()->json(['message' => 'Episode not found'], 404);
        }
        
        return response()->json(['message' => 'Episode deleted successfully']);
    }
}