<?php

    include_once('sessionManager.php');
    include_once('Sidebar.php');
    include_once('Subtopics.php');
    include_once('Article.php');
    include_once('User.php');

    if(!isset($_SESSION['email'])) {
        header("Location: errore.php?errorCode=paginaNonDisponibile");
    }

    if(!User::isAdmin($_SESSION['email'])){
        header("Location: errore.php?errorCode=nonAdmin");
    }

    if(isset($_POST['create-article']))
    {
        Article::insertArticleInTable($_POST['title'], $_POST['article-input'], $_SESSION['email'], $_POST['subtopicID']);
        header("location: ArticleLinks.php?id=".Subtopics::getTopicIDFromSubtopic($_POST['subtopicID']));
    }
    if(isset($_POST['edit-article']))
    {
        Article::editArticle($_GET['articleID'], $_POST['title'], $_POST['article-input']);
        header("location: ArticleLinks.php?id=".Subtopics::getTopicIDFromSubtopic($_POST['subtopicID']));
    }

    if(!Subtopics::checkIfSubtopicExists($_GET['subtopicID']) && !isset($_POST['submit']))
    {
        header("Location: errore.php?errorCode=404");
    }
    if(isset($_GET['articleID']) && !Article::checkIfArticleExist($_GET['articleID'])){
        header("Location: errore.php?errorCode=404");
    }

?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Scrittura Articolo &#124; DevSpace</title>
        <meta charset="UTF-8">
        <meta name="description" content="Pagina scrittura di un nuovo articolo" />
        <meta name="keywords" content="computer, science, informatica, development, teconologia, technology" />
        <meta name="language" content="italian it" />
        <meta name="author" content="Barzon Giacomo, De Filippis Francesco, Greggio Giacomo, Roverato Michele" />
        <meta name="theme-color" content="#F5F5F5" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"/>			
        <?php include_once ('favicon.php'); ?>
        <link rel="stylesheet" type="text/css" href="https://frncscdf.github.io/Tecnologie-Web/style.css" />
        <link rel="stylesheet" type="text/css" href="https://frncscdf.github.io/Tecnologie-Web/print.css" media="print"/>
        
        <script src="https://frncscdf.github.io/Tecnologie-Web/scripts.js"></script>
        <?php
            Sidebar::printSidebarIncludeHeader();
        ?>
    </head>
    <body>
        <button onclick="topFunction()" id="retTop" title="Torna su"></button>
        <div id="mobile-sidebar-mask">
        </div>
        <div id="sidebar-wrapper">
        <?php
            Sidebar::printSidebar($_GET['topicID'], $_GET['subtopicID'], true);
        ?>
        </div>
        <div id="rightSideWrapper">
            <?php
                Sidebar::printNavbar();
            ?>
            <div id="main">
                <div id="insert-article-error-box"></div>
                <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" id="write-article-form">
                    <?php
                        if(isset($_GET['articleID'])){
                            echo '<h1>Modifica un articolo</h1>';
                        }else{
                            echo '<h1>Scrivi un nuovo articolo</h1>';
                        }
                    ?>
                    <ul id="article-info">
                        <?php
                            echo '<li>Argomento: <a href="ArticleLinks.php?id='.$_GET['topicID'].'">'.Subtopics::getTopicTitle($_GET['topicID']).'</a></li>';
                            echo '<li>Sottoargomento: <a href="ArticleLinks.php?id='.$_GET['topicID'].'#subtopic_'.$_GET['subtopicID'].'">'.Subtopics::getSubtopicTitle($_GET['subtopicID']).'</a></li>';
                            if(isset($_GET['articleID'])){
                                $articleInfo = Article::getArticleRowFromId($_GET['articleID']);
                                echo '<li>Stai modificando l\'articolo: <a href="ReadArticle.php?id='.$articleInfo['Id'].'" target="_blank">'.$articleInfo['Title'].'</a></li>';
                            }
                        ?>
                    </ul>
                    <fieldset>
                        <input type="hidden" name="subtopicID" value="<?php echo $_GET['subtopicID'] ?>" />
                        <p>
                            <h2>Inserisci il titolo per il tuo articolo</h2>
                            <?php
                                if(isset($_GET['articleID'])){
                                    echo '<input type="text" name="title" required id="title" placeholder="Titolo dell\'articolo" value="'.$articleInfo['Title'].'">';
                                }else{
                                    echo '<input type="text" name="title" required id="title" placeholder="Titolo dell\'articolo">';
                                }
                            ?>
                        </p>
                        <p>
                            <h2>Scrivi il testo del tuo articolo</h2>
                            <?php
                                if(isset($_GET['articleID'])){
                                    echo '<textarea name="article-input" required rows="10" cols="100" id="new-article-content">'.$articleInfo['HTMLCode'].'</textarea>';
                                }else{
                                    echo '<textarea name="article-input" required rows="10" cols="100" id="new-article-content"></textarea>';
                                }
                            ?>
                        </p>
                        <p>Tag HTML supportati</p>
                        <?php
                            if(isset($_GET['articleID'])){
                                echo '<input type="submit" value="Modifica articolo" name="edit-article"/>';
                                echo '<a href="ArticleLinks.php?id='.$_GET['topicID'].'">Annulla modifica</a>';
                            }else{
                                echo '<input type="submit" value="Invia" name="create-article"/>';
                            }
                        ?>
                    </fieldset>                 	
                </form>
            </div>
            <?php
                include_once ('footer.php');
                Sidebar::openSidebarEntry($_GET["topicID"]);
            ?>
        </div>
    </body>
</html>
