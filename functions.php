<?php
function makeController($controllerPath, $controllerName, $baseNamespace = 'App\\Controller') {
    $template = "<?php\n\nnamespace %s;\n\nuse App\\Controller\\Controller;\n\nclass %s extends Controller\n{\n    // Controller logic\n}\n";

    $baseNamespace = rtrim($baseNamespace, '\\');
    $controllersPath = __DIR__ . '/app/Controller/' . $controllerPath;
    
    // Create directory path for controllers if it doesn't exist
    if (!file_exists($controllersPath)) {
        mkdir($controllersPath, 0777, true); 
        echo "Directory '{$controllerPath}' created successfully.\n";
    }

    // Rewrite namespace based on the directory structure
    $relativePath = trim($controllerPath, '/');
    $namespace = $baseNamespace . '\\' . str_replace('/', '\\', $relativePath);

    $content = sprintf($template, $namespace, $controllerName);

    $filename = $controllersPath . '/' . $controllerName . '.php';
    if (file_exists($filename)) {
        echo "Controller '{$controllerName}' already exists.\n";
        return;
    }

    if (file_put_contents($filename, $content) !== false) {
        echo "Controller '{$controllerName}' created successfully.\n";
    } else {
        echo "Error creating controller '{$controllerName}'.\n";
    }
}

function makeModel($modelName, $namespace = 'App\\Models') {
    // Template for the model class
    $template = "<?php\n\nnamespace %s;\n\nuse App\\Database\\Eloquent\\Model;\n\nclass %s extends Model\n{\n    // Model logic\n}\n";

    // Ensure namespace format is correct
    $namespace = rtrim($namespace, '\\');

    // Generate model content with namespace and class name
    $content = sprintf($template, $namespace, $modelName);

    // Directory path for models
    $modelsPath = __DIR__ . '/app/Models/';

    // Create models directory if it doesn't exist
    if (!file_exists($modelsPath)) {
        mkdir($modelsPath, 0777, true); 
        echo "Directory 'Models' created successfully.\n";
    }

    // File path for the model
    $filename = $modelsPath . $modelName . '.php';

    // Check if model file already exists
    if (file_exists($filename)) {
        echo "Model '{$modelName}' already exists.\n";
        return;
    }

    // Create model file
    if (file_put_contents($filename, $content) !== false) {
        echo "Model '{$modelName}' created successfully.\n";
    } else {
        echo "Error creating model '{$modelName}'.\n";
    }
}
?>
