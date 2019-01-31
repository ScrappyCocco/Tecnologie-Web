<?php
    include_once ("Connection.php");

    class Article {

        static function checkIfArticleExist($articleID){
            if((!is_numeric($articleID))){
                return false;
            }
            $value = Article::getArticleRowFromId($articleID);
            if(isset($value) && $value != NULL){
                return true;
            }else{
                return false;
            }
        }

        static function getArticleRowFromId($articleID){
            $connection = new Connection();
            $connection -> prepareQuery("SELECT * FROM ARTICLES WHERE ".$articleID." = Id");
            $result = $connection -> executeQuery();
            //Destroy the object
            $connection = NULL;
            if(isset($result[0])) {
                return $result[0];
            }else{
                return NULL;
            }
        }

        static function printArticleHTML($articleID){
            $connection = new Connection();
            $connection -> prepareQuery("SELECT * FROM ARTICLES WHERE ".$articleID." = Id");
            $result = $connection -> executeQuery();
            $connection -> prepareQuery("SELECT Nickname FROM USERS WHERE :email = Email");
            $connection->bindParameterToQuery(":email", $result[0]['AuthorID'], PDO::PARAM_STR);
            $author = $connection -> executeQuery();
            //Destroy the object
            $connection = NULL;
            echo '<h2>'.$result[0]['Title'].'</h2>';
            echo '<!--Inizio paragrafo-->
            <div class="content-paragraph">';
            echo $result[0]['HTMLCode'];
            echo '<h4 class="article-author">Autore articolo: '.$author[0]['Nickname'].'</h4>';
            echo '</div>';
        }

        static function insertArticleInTable($title, $content, $authorID, $subtopicID){
            $connection = new Connection();
            $connection -> prepareQuery(
                "INSERT INTO ARTICLES (Title, HTMLCode, AuthorID, SubtopicID)
                VALUES (:title, :code, :authorID, $subtopicID)");
            $connection->bindParameterToQuery(":title", $title, PDO::PARAM_STR);
            $connection->bindParameterToQuery(":code", $content, PDO::PARAM_STR);
            $connection->bindParameterToQuery(":authorID", $authorID, PDO::PARAM_STR);
            $result = $connection -> executeQueryDML();
            $connection = NULL;
        }

        static function getArticleTitle($articleID) {
            $connection = new Connection();
            $connection -> prepareQuery("SELECT * FROM ARTICLES WHERE ".$articleID." = Id");
            $result = $connection -> executeQuery();
            $connection = NULL;
            return $result[0]['Title'];
        }

        static function deleteArticle($articleID){
            $connection = new Connection();
            $connection -> prepareQuery(
                "DELETE FROM ARTICLES WHERE Id = $articleID");
            $result = $connection -> executeQueryDML();
            $connection = NULL;
        }

        static function editArticle($articleID, $title, $content){
            $connection = new Connection();
            $connection -> prepareQuery(
                "UPDATE ARTICLES SET Title = :title , HTMLCode = :code WHERE Id = $articleID");
            $connection->bindParameterToQuery(":title", $title, PDO::PARAM_STR);
            $connection->bindParameterToQuery(":code", $content, PDO::PARAM_STR);
            $result = $connection -> executeQueryDML();
            $connection = NULL;
        }
    }

?>