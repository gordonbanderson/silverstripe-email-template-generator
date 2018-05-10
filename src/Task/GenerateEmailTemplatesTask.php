<?php

namespace Suilven\EmailTemplateGenerator\Task;

use Carbon\Carbon;
use Faker\Factory;
use Html2Text\Html2Text;
use Jawira\CaseConverter\Convert;
use SilverStripe\Blog\Model\Blog;
use SilverStripe\Control\Director;
use SilverStripe\Dev\BuildTask;
use SilverStripe\i18n\i18n;
use SilverStripe\Security\Permission;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSTemplateParser;
use SilverStripe\View\SSViewer;
use Suilven\RealWorldPopulator\Gutenberg\Controller\GutenbergBookExtractBlogPost;
use TitleDK\Calendar\Events\Event;

/**
 * Defines and refreshes the elastic search index.
 */
class GenerateEmailTemplatesTask extends BuildTask
{
    protected $title = 'Generate Emails';

    protected $description = 'Generate email templates based on template shells and input criteria';

    private static $segment = 'email-template-generator';

    protected $enabled = true;

    public function run($request)
    {
/*
        $events = Event::get();
        foreach($events as $event) {
            $event->write();
        }
*/
        // need a book and a title slug
        //       $bookURL = $_GET['book'];

        $canAccess = (Director::isDev() || Director::is_cli() || Permission::check("ADMIN"));
        if (!$canAccess) {
            return Security::permissionFailure($this);
        }

        $templateScheme = $this->config()->get('template_scheme');
        $templateVariablesArray = $this->getTemplateArrayData();
        error_log(print_r($templateVariablesArray, 1));

        $path = dirname(__FILE__) . '/../../emailTemplateSchemes/' . $templateScheme . '/Includes';
        error_log($path);

        if (!is_dir($path)) {
            user_error("Path $path could not be found");
        }

        $cdir = scandir($path);
        foreach ($cdir as $templateFile) {
            error_log( 'TEMPLATE FILE: ' . $templateFile);

            // skip . and ..
            $fullPath = $path . '/' . $templateFile;
            if (substr($fullPath, -3) != '.ss') {
                continue;
            }

            error_log('---- PROCESSING ----' . $fullPath);

            $template = file_get_contents($fullPath);
            $output = SSViewer::execute_string($template, $templateVariablesArray);


            error_log($output);
        }

        /*
         * return $this->getParser()->compileString(
            $content,
            $template,
            Director::isDev() && SSViewer::config()->uninherited('source_file_comments')
        );
         */


    }

    /**
     * @return ArrayData configuration variables as key (camel case) => value, as per normal silverstripe templates
     */
    public function getTemplateArrayData()
    {
        $variables = $this->config()->get('template_variables');

        $templateVariablesArray = [];
        foreach ($variables as $pair) {
            $snake_key = key($pair);
            $value = $pair[$snake_key];
            $converter = new Convert($snake_key);
            $camelCaseKey = $converter->toCamel(false);
            $camelCaseKey = ucfirst($camelCaseKey);

            error_log('CONVERT: ' . $snake_key . ' --> ' . $camelCaseKey);
            $templateVariablesArray[$camelCaseKey] = $value;
        }


        print_r($templateVariablesArray);
        $templateArrayData = new ArrayData($templateVariablesArray);
        return $templateArrayData;
    }
}
