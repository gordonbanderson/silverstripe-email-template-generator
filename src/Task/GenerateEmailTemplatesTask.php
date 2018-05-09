<?php

namespace Suilven\EmailTemplateGenerator\Task;

use Carbon\Carbon;
use Faker\Factory;
use Html2Text\Html2Text;
use SilverStripe\Blog\Model\Blog;
use SilverStripe\Control\Director;
use SilverStripe\Dev\BuildTask;
use SilverStripe\i18n\i18n;
use SilverStripe\Security\Permission;
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

        /*
         * Parameters
         *
         * $ClientAdminTeamName
         *
         */



    }
}
