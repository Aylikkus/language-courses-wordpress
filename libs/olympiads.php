 <?php

class Olympiads {

    private function getTableName()
    {
        global $wpdb;
        return $wpdb->prefix . "lc_olympiad_entries"; 
    }

    public function __construct() {
        global $wpdb;

        $table_name = $this->getTableName(); 
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table_name} (
            user_id mediumint(9) NOT NULL,
            user_name text NOT NULL,
            page_id mediumint(9) NOT NULL,
            olympiad_title varchar(512) NOT NULL,
            PRIMARY KEY  (user_id, page_id, olympiad_title)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    public function InsertEntry($userId, $userName, $pageId, $olympiadTitle) {
        global $wpdb;
        $table_name = $this->getTableName();

        $wpdb->insert(
            $table_name,
            array(
                'user_id' => $userId,
                'user_name' => $userName,
                'page_id' => $pageId,
                'olympiad_title' => $olympiadTitle
            )
        );
    }

    public function GetEntriesFor($userId)
    {
        global $wpdb;
        $table_name = $this->getTableName(); 

        return $wpdb->get_results("SELECT * FROM {$table_name} WHERE user_id = {$userId}");
    }

    public function GetAllEntries($pageId, $olympiadTitle)
    {
        global $wpdb;
        $table_name = $this->getTableName(); 

        return $wpdb->get_results("SELECT * FROM {$table_name} 
            WHERE page_id = {$pageId} AND olympiad_title = '{$olympiadTitle}'");
    }
}

 ?>