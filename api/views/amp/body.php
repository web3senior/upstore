<?php
$data = $this->data[0];
$id = $data['blog_id'];
$title = $data['title'];
$content = $data['content'];
$img = $data['img'];
$tag = explode('+', $data['tag']);
$date = $data['date'];
$random_post = $this->random;
?>
<p class="heading">
    <amp-social-share type="twitter"
                      width="45"
                      height="33"></amp-social-share>
    <amp-social-share type="facebook"
                      width="45"
                      height="33"
                      data-attribution="254325784911610"></amp-social-share>
    <amp-social-share type="gplus"
                      width="45"
                      height="33"></amp-social-share>
    <amp-social-share type="email"
                      width="45"
                      height="33"></amp-social-share>
    <amp-social-share type="pinterest"
                      width="45"
                      height="33"></amp-social-share>
</p>
<amp-user-notification layout="nodisplay"
                       id="amp-user-notification1">
    This page might use cookies if your analytics vendor requires them.
    <button on="tap:amp-user-notification1.dismiss">Accept</button>
</amp-user-notification>

<section class="blog-post">
    <div class="page-title">
        <div class="row lg"><h1><?= $this->title; ?></h1></div>
    </div>
    <div class="container">
        <div class="row md">
            <div class="col sm-12 w-news-item animated fadeIn">
                <div class="progress-container">
                    <div class="progress-bar" id="myBar"></div>
                </div>

                <div class="content">
                    <?= $content; ?>
                    <a class="btn-back mt-20" href="<?= URL; ?>blog">Back</a>
                </div>

                <div class="tags mt-40">
                    <?php
                    foreach ($tag as $value):
                        echo '<a class="btn-tag" href="' . URL . 'blog/p/' . $id . '/' . (new Regular)->urlFriendly($value) . '">' . $value . '</a>';
                    endforeach;
                    ?>
                </div>

                <div class="relate">
                    <div class="container">
                        <h3 class="text-left mt-10 mb-10">Related posts:</h3>
                        <div class="row">
                            <?php
                            foreach ($random_post as $key => $value) {
                                $id = $value["blog_id"];
                                $title = $value["title"];
                                $img = $value['img'];
                                $category = $value['category_id'];
                                ?>
                                <div class="col md-6">
                                    <div class="inner">
                                        <template type="amp-mustache">
                                            <a class="card related"
                                               href="<?= URL; ?>blog/post/<?= $id; ?>">
                                                <amp-img width="101"
                                                         height="75"
                                                         src="<?= URL . 'uploads/images/'; ?><?= $img; ?>"></amp-img>
                                                <span><?= $title; ?></span>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="application/ld+json">
    {
    "@context": "http://schema.org",
    "@type": "NewsArticle",
    "mainEntityOfPage": "<?= (URL . 'blog/post/' . $id) ?>",
    "headline": "<?= $this->title ?>",
    "datePublished": "<?= $date ?>",
    "dateModified": "<?= $date ?>",
    "description": "<?= $content ?>",
    "author": {
    "@type": "Person",
    "name": "Amir Rahimi"
    },
    "publisher": {
    "@type": "Organization",
    "name": "Google",
    "logo": {
    "@type": "ImageObject",
    "url": "http://cdn.ampproject.org/logo.jpg",
    "width": 600,
    "height": 60
    }
    },
    "image": {
    "@type": "ImageObject",
    "url": "https://ampbyexample.com/img/landscape_lake_1280x857.jpg",
    "height": 1280,
    "width": 857
    }
    }


</script>