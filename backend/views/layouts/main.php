<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);




$route = Yii::$app->controller->getRoute();
$routeArray = explode('/', $route);
array_pop($routeArray);
$controllerName = implode('/', $routeArray);

$this->registerCssFile('@web/statics/css/slidebars.css', ['depends'=>'backend\assets\AppAsset']);
function isSubUrl($menuArray, $route)
{

    if (isset($menuArray) && is_array($menuArray)) {

        if (isset($menuArray['items'])) {
            foreach ($menuArray['items'] as $item)
            {
                if (isSubUrl($item, $route)) {
                    return true;
                }
            }
        } else {
            $url = is_array($menuArray['url']) ? $menuArray['url'][0] : $menuArray['url'];
            if (strpos($url, $route)) {
                return true;
            }
        }
    } else {
        $url = is_array($menuArray['url']) ? $menuArray['url'][0] : $menuArray['url'];
        if (strpos($url, $route)) {
            return true;
        }
    }
    return false;

}

function isSubMenu($menuArray, $controllerName)
{

    if (isset($menuArray) && is_array($menuArray)) {

        if (isset($menuArray['items'])) {
            foreach ($menuArray['items'] as $item)
            {
                if (isSubMenu($item, $controllerName)) {
                    return true;
                }
            }
        } else {
            $url = is_array($menuArray['url']) ? $menuArray['url'][0] : $menuArray['url'];
            if (strpos($url, $controllerName.'/')) {
                return true;
            }
        }
    } else {
        $url = is_array($menuArray['url']) ? $menuArray['url'][0] : $menuArray['url'];
        if (strpos($url, $controllerName.'/')) {
            return true;
        }
    }
    return false;

}



function initMenu($menuArray, $controllerName, $isSubUrl, $isShowIcon=false)
{
    if (isset($menuArray) && is_array($menuArray)) {

        $url = is_array($menuArray['url']) ? $menuArray['url'][0] : $menuArray['url'];

        if (empty($isSubUrl)) {
            $isSubMenu = isSubMenu($menuArray, $controllerName);
        } else {
            $route = Yii::$app->controller->getRoute();
            $isSubMenu = isSubUrl($menuArray, $route);
        }
        if ($isSubMenu) {
            $class = ' active ';
        } else {
            $class = '';
        }

        if (isset($menuArray['items'])) {
            echo '<li class="sub-menu">';
        } else {
            echo '<li class="'.$class.'">';
        }
        $url = $url == '#' ? 'javascript:;' : Url::toRoute($url);
        echo '<a href="'.$url.'"  class="'.$class.'">'.($isShowIcon ? '<i class="fa fa-sitemap"></i>' : '').'<span>'.$menuArray['label'].'</span></a>';

        if (isset($menuArray['items'])) {
            echo '<ul class="sub">';
            foreach ($menuArray['items'] as $item)
            {
                echo initMenu($item, $controllerName, $isSubUrl);
            }
            echo '</ul>';
        }

        echo '</li>';
    }

}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<section id="container" >
    <!--header start-->
    <header class="header white-bg">
        <div class="sidebar-toggle-box">
            <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
        </div>
        <!--logo start-->
        <a href="<?=Url::home()?>" class="logo">投票系统后台</a>
        <!--logo end-->
        <div class="nav notify-row" id="top_menu">
            <!--  notification start -->
            <ul class="nav top-menu">
                <!-- settings start -->

                <!-- settings end -->
                <!-- inbox dropdown start-->


            </ul>
            <!--  notification end -->
        </div>
        <div class="top-nav ">
            <!--search & user info start-->
            <ul class="nav pull-right top-menu">
<!--                <li>-->
<!--                    <input type="text" class="form-control search" placeholder="Search">-->
<!--                </li>-->
                <!-- user login dropdown start-->
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <img alt="" src="<?=Yii::getAlias('@web')?>/statics/img/avatar1_small.jpg">
                        <span class="username"><?=Yii::$app->user->identity['username']?></span>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu extended logout">
                        <div class="log-arrow-up"></div>
                        <li><a href="index.php?r=profile/index"><i class=" fa fa-suitcase"></i>Profile</a></li>
<!--                        <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>-->
                        <li><a href="<?=Url::toRoute('/site/logout')?>" data-method="post"><i class="fa fa-key"></i> Log Out</a></li>
                    </ul>
                </li>
                <li class="sb-toggle-right">
                    <i class="fa  fa-align-right"></i>
                </li>
                <!-- user login dropdown end -->
            </ul>
            <!--search & user info end-->
        </div>
    </header>
    <!--header end-->
    <!--sidebar start-->
    <aside>
        <div id="sidebar"  class="nav-collapse ">
            <!-- sidebar menu start-->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left info">
                    </div>
                </div>
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <?= $this->render('//layouts/sidebar-menu') ?>
            </section>

            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <?=$content?>
    </section>
    <!--main content end-->



    <!--footer start-->
    <footer class="site-footer">
        <div class="text-center">
            2018 &copy; FlatLab by GalaxyIdea.
            <a href="#" class="go-top">
                <i class="fa fa-angle-up"></i>
            </a>
        </div>
    </footer>
    <!--footer end-->
</section>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
