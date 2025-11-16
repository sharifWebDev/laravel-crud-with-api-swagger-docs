<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="My Laravel API",
 *     version="1.0.0",
 *     description="API documentation for Laravel project",
 *     @OA\Contact(
 *         name="API Support",
 *         url="http://example.com/support",
 *         email="support@example.com"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Local Laravel API Server"
 * )
 *
 * @OA\Tag(
 *     name="Students",
 *     description="Operations related to student management"
 * )
 */
class SwaggerInfo
{
    // This class is only for Swagger/OpenAPI annotations
    
}
