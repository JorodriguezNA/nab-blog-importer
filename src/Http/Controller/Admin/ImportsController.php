<?php namespace Nabmarketingteam\BlogimporterModule\Http\Controller\Admin;

use Nabmarketingteam\BlogimporterModule\Import\Form\ImportFormBuilder;
use Nabmarketingteam\BlogimporterModule\Import\Table\ImportTableBuilder;
use Anomaly\Streams\Platform\Http\Controller\AdminController;
use Anomaly\PostsModule\Post\PostRepository;
use Anomaly\PostsModule\Post\Contract\PostRepositoryInterface;
use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Anomaly\PostsModule\Category\Contract\CategoryRepositoryInterface;
use Anomaly\PostsModule\Type\Contract\TypeRepositoryInterface;
use Anomaly\Streams\Platform\Database\Seeder\Seeder;
use Anomaly\Streams\Platform\Entry\EntryRepository;
use Anomaly\Streams\Platform\Model\Posts\PostsDefaultPostsEntryModel;
use Anomaly\UsersModule\User\UserSeeder;
use Anomaly\UsersModule\Role\Contract\RoleRepositoryInterface;
use Anomaly\UsersModule\User\Contract\UserInterface;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Anomaly\UsersModule\User\UserActivator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Anomaly\FilesModule\File\FileUploader;
use Anomaly\FilesModule\File\Contract\FileRepositoryInterface;
use Anomaly\FilesModule\Folder\Contract\FolderRepositoryInterface;



class ImportsController extends AdminController
{



    /**
     * Display an index of existing entries.
     *
     * @param ImportTableBuilder $table
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ImportTableBuilder $table)
    {
        return $table->render();
    }

    /**
     * Create a new entry.
     *
     * @param ImportFormBuilder $form
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(ImportFormBuilder $form)
    {
        return $form->render();
    }

    /**
     * Edit an existing entry.
     *
     * @param ImportFormBuilder $form
     * @param        $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(ImportFormBuilder $form, $id)
    {
        return $form->render($id);
    }

    /**
     * Show the list of posts to import.
     *
     * @param ImportFormBuilder $form
     * @param        $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function import($id, PostRepositoryInterface $posts, UserRepositoryInterface $users)
    {   

        $siteinfo = DB::table('blogimporter_importers_translations')->where('entry_id', $id)->first();
        $getblog = curl_init();
        curl_setopt($getblog, CURLOPT_URL, 'http://templatr.nabamarketingteam.com/getblogposts/'.$siteinfo->site_name.'/'.$siteinfo->blog_id.'/0');
        curl_setopt($getblog, CURLOPT_RETURNTRANSFER, true);
        $bloginfo = curl_exec($getblog);
        curl_close($getblog);
        $bloginfo = json_decode($bloginfo);
        foreach ($bloginfo as $blogPost) {
            $slug = str_replace("blog/", "", $blogPost->slug);
            $ispost = $posts->findBySlug($slug);
            $author = $blogPost->blog_post_author;
            if(isset($author->email))
            {
                $authoremail = $author->email;
            }else{
            $authoremail = 'none ' . $slug;
            }
            $isauthor = $users->findByEmail($authoremail);
            if($isauthor == null)
            {
                $blogPost->importAuthor = true;
            }else
            {
                $blogPost->importAuthor = false;
            }
            if($ispost == null)
            {
                $blogPost->isimported = false;
            }else{
                $blogPost->isimported = true;
            }
        }
        //return json_encode($siteinfo);
        return $this->view->make('module::admin/imports/import', ['blog_info' => $bloginfo, 'importer_id' => $id, 'author' => $siteinfo]);
    }


    // Controller for importing a specific post.
    public function importpost($siteid, $postid, UserRepositoryInterface $users, PostRepositoryInterface $posts, TypeRepositoryInterface $types,CategoryRepositoryInterface $categories, Filesystem $filesystem, FileUploader $uploader, FileRepositoryInterface $files, FolderRepositoryInterface $folders, PostsDefaultPostsEntryModel $postContent)
    {
        // Get Post Data
        $siteinfo = DB::table('blogimporter_importers_translations')->where('entry_id', $siteid)->first();
        $blogPostData = file_get_contents('https://api.hubapi.com/content/api/v2/blog-posts/'.$postid.'?hapikey='.$siteinfo->site_key);
        $blogPost = json_decode($blogPostData);

        // Check if Author needs to be created, then create Author and get Author ID.
        $author = $blogPost->blog_post_author;
        if(isset($author->email))
        {
            $authoremail = $author->email;
        }else{
        $authoremail = 'none ' . $slug;
        }
        $isauthor = $users->findByEmail($authoremail);
        if($isauthor == null)
        {
            $isauthor = $users->findByEmail($siteinfo->default_author);
            $blogPost->importAuthor = true;
        }else{
            $author = $blogPost->blog_post_author;
        }

        $tagnames = '';
        // Get Tags
        for($i = 0; $i < sizeof($blogPost->tag_ids); $i++)
        {
            $tag = $blogPost->tag_ids[$i];
            $tagData = json_decode(file_get_contents('https://api.hubapi.com/blogs/v3/topics/'.$tag.'?hapikey='.$siteinfo->site_key));
            $tagnames = $tagnames . ' ' . $tagData->name.',';
            //array_push($tagnames, $tagData->name);
        }

        //Start prepping the post for import
        $slug = str_replace("blog/", "", $blogPost->slug);
        $folder = $folders->findBySlug('s3_storage');
        $ispost = $posts->findBySlug($slug);

        
        $repository = new EntryRepository();
        $repository->setModel(new PostsDefaultPostsEntryModel());
        //$repository->truncate();
        $type     = $types->findBySlug('default');
        $category = $categories->findBySlug('news');
        $featured_image_path = pathinfo($blogPost->featured_image);
        $featureImageName = $featured_image_path['filename'];
        $pathRedirect = '../../../../';
        if($blogPost->featured_image != "")
        {
            copy($blogPost->featured_image, dirname(__FILE__, 5).'/resources/seeder_gallery/'. $featured_image_path['basename']);
        }
        $curlFile = curl_init($blogPost->featured_image);
        curl_setopt($curlFile, CURLOPT_NOBODY, true);
        curl_setopt($curlFile, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlFile, CURLOPT_HEADER, true);
        curl_setopt($curlFile, CURLOPT_FOLLOWLOCATION, true);

        $filedata = curl_exec($curlFile);
        curl_close($curlFile);

        if (preg_match('/Content-Length: (\d+)/', $filedata, $matches)) {
            $contentLength = (int)$matches[1];
        }

        $featureImageContentType = exif_imagetype($blogPost->featured_image);
        $featureImage = new UploadedFile(
            dirname(__FILE__, 5).'/resources/seeder_gallery/'. $featured_image_path['basename'],
            urlencode($featured_image_path['basename']),
            $featureImageContentType,
            $contentLength,
            null,
            true
        );
        //var_dump($files->findByNameAndFolder($featured_image_path['basename'], $folder));

        //echo json_encode($files->findByNameAndFolder($featured_image_path['basename'], $folder));
        //exit;
        /* if($files->findByNameAndFolder($featured_image_path['basename'], $folder) === null)
        {
            $fileUpload = $uploader->upload($featureImage, $folder);
            $file = $files->findByNameAndFolder($featured_image_path['basename'], $folder);
            echo "uploaded";
        }else
        {
            $file = $files->findByNameAndFolder($featured_image_path['basename'], $folder);

        } */

        $fileUpload = $uploader->upload($featureImage, $folder);
        $file = $files->findByNameAndFolder($featured_image_path['basename'], $folder);
        
        $repository = new EntryRepository();
        

        $repository = new EntryRepository();

        $repository->setModel(new PostsDefaultPostsEntryModel());

        $postcontent =  (new PostsDefaultPostsEntryModel())->create(
            [
                
                'en' => [
                    'content' => $blogPost->post_body,
                    
                ],
                'featured_image_id' => $file->id,
                'preview_snippet'   => strip_tags($blogPost->post_summary),
            ]

        );

        $posts->create(
            [
                'en'         => [
                    'title'   => $blogPost->name,
                    'summary' => strip_tags($blogPost->post_summary),
                    'meta_description' => $blogPost->meta_description,
                ],
                'slug'       => $slug,
                'publish_at' => $blogPost->publish_date / 1000,
                'enabled'    => true,
                'type'       => $type,
                'entry'      => $postcontent,
                'category'   => $category,
                'author'     => $isauthor->id,
                'tags'       => $tagnames,
            ]
        );

       

        

        return json_encode(array('success' => true, 'message' => "Post Successfully Created."));

    }

    public function createauthor(
        $siteid, 
        $authoremail,
        UserRepositoryInterface $users,
        RoleRepositoryInterface $roles,
        UserActivator $activator)
    {
        $siteinfo = DB::table('blogimporter_importers_translations')->where('entry_id', $siteid)->first();
        $isauthor = $users->findByEmail($authoremail);
        $authorRole = $roles->findBySlug('author');
        if($isauthor == null)
        {
            $userDataFile = file_get_contents('https://api.hubapi.com/blogs/v3/blog-authors/search/?q='.$authoremail.'&hapikey='.$siteinfo->site_key);
            
            $userData = json_decode($userDataFile);
            $userData = $userData->objects[0];
            //return json_encode($userData);
            //exit;
            $alpha_key = '';
            $keys = range('A', 'Z');

            for ($i = 0; $i < 2; $i++) {
                $alpha_key .= $keys[array_rand($keys)];
            }

            $length = 17 - 2;

            $key = '';
            $keys = range(0, 9);

            for ($i = 0; $i < $length; $i++) {
                $key .= $keys[array_rand($keys)];
            }

            $pwd = $alpha_key . $key;
            $users->unguard();
            $name = $userData->fullName;
            $name = trim($name);
            $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
            $first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
            $createdauthor = $users->create([
                'first_name'    => $first_name,
                'last_name'     => $last_name,
                'display_name'  => $userData->displayName,
                'email'         => $userData->email,
                'password'      => $pwd,
                'username'      => $userData->slug,
            ]);
            $createdauthor->roles()->sync([$authorRole->getId()]);
            $activator->force($createdauthor);


            return array('success' => true, 'user_data' => json_encode($users->findByEmail($userData->email)));
        }else{
            return json_encode($isauthor);
        }
        
    }

    public function truncate(PostRepositoryInterface $posts, PostsDefaultPostsEntryModel $postContent)
    {
        $repository = new EntryRepository();

        $repository->setModel(new PostsDefaultPostsEntryModel());
        $posts->truncate();
        $repository->truncate();
        $postContent->truncate();

        return json_encode(array('success' => true));
    }
}
