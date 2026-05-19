<?php
/**
 * ============================================================
 * Class App - Router / Dispatcher Utama
 * ============================================================
 * 
 * Alur Request:
 * 1. User akses URL → masuk ke public/index.php
 * 2. App::run() membaca URL dari $_GET['url']
 * 3. Cocokkan URL dengan route yang didefinisikan di routes/web.php
 * 4. Panggil controller dan method yang sesuai
 * 5. Controller proses data → kirim ke view
 * 
 * Contoh:
 *   URL: /rooms/detail/5
 *   → Controller: RoomController
 *   → Method: detail
 *   → Parameter: 5
 */
class App
{
    private static $routes = [];

    /**
     * Daftarkan route GET
     */
    public static function get($path, $action)
    {
        self::$routes['GET'][$path] = $action;
    }

    /**
     * Daftarkan route POST
     */
    public static function post($path, $action)
    {
        self::$routes['POST'][$path] = $action;
    }

    /**
     * Jalankan routing
     */
    public static function run()
    {
        // Ambil URL dari query string
        $url = isset($_GET['url']) ? '/' . trim($_GET['url'], '/') : '/';
        $method = $_SERVER['REQUEST_METHOD'];

        // Cari route yang cocok
        $matched = false;

        if (isset(self::$routes[$method])) {
            foreach (self::$routes[$method] as $route => $action) {
                // Konversi route pattern: {id} → regex (\d+)
                $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '(\d+)', $route);
                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $url, $matches)) {
                    array_shift($matches); // Hapus full match

                    // Parse action: "Controller@method"
                    list($controllerName, $methodName) = explode('@', $action);

                    // Load controller file
                    $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
                    if (file_exists($controllerFile)) {
                        require_once $controllerFile;
                        $controller = new $controllerName();

                        // Panggil method dengan parameter
                        call_user_func_array([$controller, $methodName], $matches);
                        $matched = true;
                        break;
                    }
                }
            }
        }

        // Jika tidak ada route yang cocok → 404
        if (!$matched) {
            http_response_code(404);
            require_once __DIR__ . '/../views/errors/404.php';
        }
    }
}
