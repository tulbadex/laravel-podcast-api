<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Podcast Platform API",
 *     version="1.0.0",
 *     description="API documentation for the Podcast Platform",
 *     @OA\Contact(
 *         email="support@example.com",
 *         name="API Support"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="/api",
 *     description="API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 */
abstract class Controller
{
    //
}
