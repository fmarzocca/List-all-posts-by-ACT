<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/*****************************************************************
*
* 
* a hierarchical list of all posts by nested categories, post title and authors
* © Fabio Marzocca - 2015-2024
*
* Frontend
******************************************************************/

$firtsrun = 0;

function ACT_hierarchy_indexes($atts)
{
    global $firstrun;

    if (!isset($_POST['order'])) {
        $firstrun = 1;
        $_POST['order']="category";
    }


    if ($atts['singleuser']) {
        $atts['admin'] = "1";
    }
    ob_start();
    
    echo '<div class="ACT-wrapper">';
    if (count(explode(",", $atts['show'])) > 1) :
?>

<form name="form1" method="post" >
     <?php wp_nonce_field( 'ACT_form_displayer', 'ACT_nonce_display' ); ?>
        <div align="center" class="styled-select"><?php esc_html_e("Group by:", 'list-all-posts-by-authors-nested-categories-and-titles') ?> 
          <select name="order"  id="order"  onChange=" ;this.form.submit();">
            <?php if (!($atts['singleuser']) and strpos($atts['show'], "Author") !== false) : ?>
            <option value="author" <?php if ($_POST['order']  == "author") {
                echo "selected";
                                   } ?>><?php esc_html_e("Author", 'list-all-posts-by-authors-nested-categories-and-titles'); ?> </option>
            <?php endif; ?>
            <?php if (strpos($atts['show'], "Title") !== false) : ?>
            <option value="title" <?php if ($_POST['order']  == "title") {
                echo "selected";
                                  } ?>><?php esc_html_e("Title", 'list-all-posts-by-authors-nested-categories-and-titles'); ?> </option>
            <?php endif; ?>
                <?php if (strpos($atts['show'], "Category") !== false) : ?>
            <option value="category" <?php if ($_POST['order']  == "category") {
                echo "selected";
                                     } ?>><?php esc_html_e("Category", 'list-all-posts-by-authors-nested-categories-and-titles'); ?> </option>
                <?php endif; ?>
          </select>
        </div>
    </form>
<?php
    endif;
    
    if (!$firstrun ){
        if (
            ! isset( $_POST['ACT_nonce_display'] ) ||
            ! wp_verify_nonce(
                    sanitize_text_field( wp_unslash( $_POST['ACT_nonce_display'] ) ),
                    'ACT_form_displayer'
            )
        ) {
                // Nonce is not valid, handle the error
                die("Not authorized");
        }
               $firstrun = 0;
    }

    if (count(explode(",", $atts['show'])) == 1) :
        $_POST['order'] = strtolower($atts['show']);
    endif;
    
    if (count(explode(",", $atts['show'])) == 2 and strpos($atts['show'], "Category") === false) :
        $_POST['order'] = "author";
    endif;

    if ($_POST['order']  == "author") {
        ACT_byauthor($atts);
    } elseif ($_POST['order']  == "title") {
        ACT_bytitle($atts);
    } else {
        ACT_bycategory($atts);
    }
    echo "</div> <!-- ACT-wrapper -->";
    $output_string=ob_get_contents();
    ob_end_clean();
    return $output_string;
}
    
function ACT_bycategory($atts)
{
    /* Start browsing categories*/
    foreach (get_categories('hide_empty=0') as $cat) :
        if (strpos($atts['exclude'], $cat->slug)!== false) :
            continue;
        endif;
    
        if (!$cat->parent) {
             echo "<h4><a href='".esc_html(get_category_link($cat))."'>".esc_html($cat->name)."</a></h4><ul>";
            ACT_traverse_cat_tree( $cat->term_id, $atts);
        }
    endforeach;
}


function ACT_traverse_cat_tree($cat, $atts)
{
    $postargs = array(
        'public'   => true,
        '_builtin' => false
    );
    $post_types = get_post_types( $postargs);

    array_push($post_types, 'post');
    $ordering = 'DESC';
    if ($atts['reverse-date']) {
        $ordering = 'ASC';
    }
    $args = array('category__in' => array( $cat ), 'numberposts' => -1, 'order' => $ordering, 'post_type' => $post_types);
    
    $cat_posts = get_posts( $args );
    
    if ($cat_posts) :
        $i = 0;
        foreach ($cat_posts as $post) :
            /* exclude admin?  */
            if (!$atts['admin']) {
                if (is_super_admin($post->post_author)) :
                    continue;
                endif;
            }
            echo '<li class="subpost">';
           $postdate = date_i18n( get_option( 'date_format' ), strtotime($post->post_date)).' - ';
    echo ($atts['postdate'] ? esc_html($postdate) : ''). '<a href="' . esc_html(get_permalink( $post->ID) ) . '">' . esc_html($post->post_title) . '</a>';
            if (!($atts['singleuser'])) :
                echo "<span class='righttext'>[".esc_html(get_the_author_meta( 'first_name', $post->post_author ))." ".esc_html(get_the_author_meta( 'last_name', $post->post_author ))."]</span>";
            endif;
    
            echo '</li>';
            $i++;
            if ($atts['postspercategory'] > -1) :
                if ($i >= $atts['postspercategory']) :
                    break;
                endif;
            endif;
        endforeach;
    endif;
    $next = get_categories('hide_empty=0&parent=' . $cat);
 
    if ($next) :
        foreach ($next as $cat) :
            if (strpos($atts['exclude'], $cat->slug)!== false) :
                continue;
            endif;
               echo "<ul><li class='subcat'><a href='".esc_html(get_category_link($cat))."'>".esc_html($cat->name)."</a></li>";
              ACT_traverse_cat_tree( $cat->term_id, $atts);
        endforeach;
    endif;
    echo '</ul>';
}


function ACT_bytitle($atts)
{
    $postargs = array(
        'public'   => true,
        '_builtin' => false
    );
    $post_types = get_post_types( $postargs);

    array_push($post_types, 'post');

    if ($atts['totalpoststitle'] > -1) {
        $args = array(  'posts_per_page' => -1, 'post_type' => $post_types);
    } else {
        $args = array(  'posts_per_page' => -1,
                    'orderby' => 'title' ,
                    'post_type' => $post_types,
                    'order' => 'ASC');
    }
    $articles = get_posts($args);
    echo "<h4></h4>";
    if ($articles) :
        echo "<ul>";
        $i = 0;
        foreach ($articles as $article) :
        /* excluded categories  */
            if (has_category(explode(',', $atts['exclude']), $article->ID)) :
                continue;
            endif;
            
        /* include admin? */
            if (!$atts['admin']) {
                if (is_super_admin($article->post_author)) :
                    continue;
                endif;
            }
            echo '<li>';
            $postdate = date_i18n( get_option( 'date_format' ), strtotime($article->post_date)).' - ';
            echo ($atts['postdate'] ? esc_html($postdate) : '').'<a href="' . esc_html(get_permalink( $article->ID )) . '">' . esc_html($article->post_title) . '</a>';
            if (!($atts['singleuser'])) :
                echo "<span class='righttext'>[".esc_html(get_the_author_meta( 'first_name', $article->post_author ))." ".esc_html(get_the_author_meta( 'last_name', $article->post_author ))."]</span>";
            else :
                $categories = get_the_category( $article->ID );
                $list_cats =null;
                foreach ($categories as $cat) :
                    $list_cats .= esc_html($cat->name).", ";
                endforeach;
                $list_cats = substr($list_cats, 0, -2);
                echo "<span class='righttext'>[".esc_html($list_cats)."]</span>";
            endif;
            echo '</li>';
            $i++;
            if ($atts['totalpoststitle'] > -1) :
                if ($i >= $atts['totalpoststitle']) :
                    break;
                endif;
            endif;
        endforeach;
        echo "</ul>";
    endif;
}

function ACT_byauthor($atts)
{
    $param = 'blog_id=1&orderby=nicename';
    $autori= get_users( $param );

    foreach ($autori as $user) :
        /*check if excluded admin */
        if (!$atts['admin']) {
            if (is_super_admin($user->ID)) :
                continue;
            endif;
        }
        /* Array of WP_User objects */
        $postargs = array(
        'public'   => true,
        '_builtin' => false
            );
            $post_types = get_post_types( $postargs);

            array_push($post_types, 'post');
    
            $ordering = 'DESC';
        if ($atts['reverse-date']) {
            $ordering = 'ASC';
        }
    
        $args= array(
            'author'        =>  $user->ID,
            'post_type'   => $post_types,
            'posts_per_page' =>  -1,
            'category__not_in' => get_cats_by_slug(explode(',', $atts['exclude']))
                );
        if ($atts['postsperauthor'] == -1) {
            array_push($args,'order',$ordering );
        }
            $author_posts=  get_posts( $args );
        if (!$author_posts) :
            continue;
        endif;
            echo '<h4><a href="'.esc_html(get_author_posts_url($user->ID)).'">'.esc_html($user->display_name).'</a></h4>';
    
        if ($author_posts) {
            echo '<ul>';
            $i = 0;
            foreach ($author_posts as $author_post) {
                $postdate = date_i18n( get_option( 'date_format' ), strtotime($author_post->post_date)).' - ';  
                echo '<li>';
                echo ($atts['postdate'] ? esc_html($postdate) : ''). '<a href="' . esc_html(get_permalink( $author_post->ID )) . '">'.esc_html($author_post->post_title).'</a>';
                $categories = get_the_category( $author_post->ID );
                $list_cats =null;
                foreach ($categories as $cat) :
                    $list_cats .= esc_html($cat->name).", ";
                endforeach;
                $list_cats = substr($list_cats, 0, -2);
                echo "<span class='righttext'>[".esc_html($list_cats)."]</span>";
                echo '</li>';
                $i++;
                if ($atts['postsperauthor'] > -1) :
                    if ($i >= $atts['postsperauthor']) :
                        break;
                    endif;
                endif;
            }
        }
            echo '</ul>';
    endforeach;
}

function get_cats_by_slug($catslugs) {
    $catids = array();
    foreach($catslugs as $slug) {
        if (!get_category_by_slug($slug) ) break;
        $catids[] = get_category_by_slug($slug)->term_id;
    }
    return $catids;
}

?>
