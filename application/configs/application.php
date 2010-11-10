[production]
phpSettings.display_startup_errors = 0
phpSettings.display_errors = 0

includePaths.library = APPLICATION_PATH "/../library"
includePaths.doctrine = APPLICATION_PATH "/../library/Doctrine/lib"

bootstrap.path = APPLICATION_PATH "/Bootstrap.php"
bootstrap.class = "Bootstrap"

appnamespace = "Application"

autoloaderNamespaces.0 = "App_"
autoloaderNamespaces.1 = "Doctrine_"

doctrine.dsn.adapter = "pdo_mysql"
doctrine.dsn.host = ""
doctrine.dsn.username = "project"
doctrine.dsn.password = "2MA9e7nzrhAwbuHL"
doctrine.dsn.dbname = "project"

doctrine.models_path = APPLICATION_PATH "/models"
doctrine.cli_mode =

auth.salt = e2c1c54545dc635746d4657f50db831762b8f3d6
auth.reset.maxInterval = 345600 ;4 days
auth.lockOut.maxAttempts = 5
auth.lockOut.unlockInterval = 86400 ;24 hours
session.length = 604800 ;1 week

resources.frontController.controllerDirectory = APPLICATION_PATH "/controllers"
resources.frontController.params.displayExceptions = 0
resources.frontController.baseUrl = "/"
resources.frontController.plugins.moduleSetup = App_Controller_Plugin_ModuleSetup
resources.frontController.plugins.auth = App_Controller_Plugin_Auth
resources.frontController.moduleDirectory = APPLICATION_PATH "/modules"
resources.modules[] =

resources.layout.layout = "default"
resources.layout.layoutPath = APPLICATION_PATH "/views/layouts"

resources.view[] =
resources.view.helperPath.App_View_Helper = APPLICATION_PATH "/views/helpers"
resources.view.scriptPath.default = APPLICATION_PATH "/views/scripts"

resources.router.routes.default.route = ":controller/:action/*"
resources.router.routes.default.defaults.module = "default"
resources.router.routes.default.defaults.controller = "index"
resources.router.routes.default.defaults.action = "index"

resources.router.routes.account.route = "account/:controller/:action/*"
resources.router.routes.account.defaults.module = "account"
resources.router.routes.account.defaults.controller = "index"
resources.router.routes.account.defaults.action = "index"

filepath.upload.image = APPLICATION_PATH "/../public/uploads/images"
webpath.upload.image = "/uploads/images"
filepath.upload.file = APPLICATION_PATH "/../public/uploads/files"
webpath.upload.file = "/uploads/files"

email.alert.from.name = "Project"
email.alert.from.address = martin.shopland@redbrickstudios.co.uk
email.alert.useTestAddress = false

[staging : production]

[testing : production]
phpSettings.display_startup_errors = 1
phpSettings.display_errors = 1
resources.frontController.params.displayExceptions = 1

[development : production]
phpSettings.display_startup_errors = 1
phpSettings.display_errors = 1
resources.frontController.params.displayExceptions = 1

doctrine.dsn.host = "localhost"

email.alert.from.name = "Project Dev"

[doctrineCLI : production]
phpSettings.display_startup_errors = 1
phpSettings.display_errors = 1

doctrine.dsn.host = "localhost"

doctrine.cli_mode = 1
doctrine.data_fixtures_path = APPLICATION_PATH "/configs/data/fixtures"
doctrine.sql_path           = APPLICATION_PATH "/configs/data/sql"
doctrine.migrations_path    = APPLICATION_PATH "/configs/migrations"
doctrine.yaml_schema_path   = APPLICATION_PATH "/configs/schema.yml"
doctrine.generate_models_options.pearStyle = true
doctrine.generate_models_options.generateTableClasses = true
doctrine.generate_models_options.generateBaseClasses = true
doctrine.generate_models_options.baseClassPrefix = "Base_"
doctrine.generate_models_options.baseClassesDirectory =
doctrine.generate_models_options.classPrefixFiles = false
doctrine.generate_models_options.classPrefix = "Model_"