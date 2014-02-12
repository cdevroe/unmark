## Custom Directory
The custom directory is so that you can easily extend the base open source install of Nilai. You can easily overwrite models, controllers, routes, etc. All you need to do is mirror the directory and files you want to override from the `/applications` folder. The custom loader will check under `/custom` for the file first. If found, use that. If not move to `/application` folder.

Any config/route files will load both. Meaning if you redefine `/custom/configs/routes.php`, the loader will load the application version first and then your custom one. That way you can just overwrite or add what you need to configuration files and not have to redefine the entire file.

If you want controllers, models or libraries to extend application version just make it so. If you rather they extend the base `CI_Controller` you can do that too.

### Loading custom registration controller
Just add a `register.php` file to your `/custom/controllers/` folder.

IE: `/custom/controllers/register.php`

#### Code Sample
```
class Register extends Plain_Controller
{
    //...
}
```