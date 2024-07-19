 <?php

class Scores {

    private function getTableName()
    {
        global $wpdb;
        return $wpdb->prefix . "lc_test_scores"; 
    }

    public function __construct() {
        global $wpdb;

        $table_name = $this->getTableName(); 
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id mediumint(9) NOT NULL,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            score mediumint(9) NOT NULL,
            percent mediumint(9) NOT NULL,
            testInfo text NOT NULL,
            refs text,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    public function InsertScore($userId, $score, $testInfo, $percent, $refs) {
        global $wpdb;
        $table_name = $this->getTableName(); 

        $tz = 'Europe/Moscow';
        $timestamp = time();
        $dt = new DateTime("now", new DateTimeZone($tz));
        $dt->setTimestamp($timestamp);

        $wpdb->insert(
            $table_name,
            array(
                'user_id' => $userId,
                'time' => $dt->format('Y-m-d H:i:s'),
                'score' => $score,
                'testInfo' => $testInfo,
                'percent' => $percent,
                'refs' => $refs
            )
        );
    }

    public function GetScoresFor($userId)
    {
        global $wpdb;
        $table_name = $this->getTableName(); 

        return $wpdb->get_results("SELECT * FROM {$table_name} WHERE user_id = {$userId} ORDER BY time DESC");
    }
}

 ?>