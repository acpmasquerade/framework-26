<?php

    /** 
     * The Pagination Class 
     **/
	class Helper_Pagination extends Helper{
		public static function generate_html($page_prefix, $max_results = NULL){
			// build pagination query block from GET arguments
			if(isset($_GET["page_number"])){
				$page_number = intval($_GET['page_number']);
				if($page_number <= 0 ){
					$page_number = 1;
				}
			}else{
				$page_number = 1;
			}

			if(isset($_GET["limit"])){
				$limit = $_GET['limit'];
			}else{
				$limit = DB::default_limit;
			}

			return build_pagination_links($page_number, 
    								$limit, 
    								$max_results, 
    								$page_prefix, 
    								NULL,
    								NULL,
    								FALSE,
    								TRUE);
		}

        /**
         * Builds pagination links wrapped by a div with class=pagination
         *
         * @param mixed $page_number
         * @param mixed $limit
         * @param mixed $max_results
         * @param mixed $page_prefix
         * @param mixed $page_suffix
         * @author acpmasquerade
         */
        public static function build_pagination_links($page_number = 1, 
                                        $limit = DB::default_limit, 
                                        $max_results, 
                                        $page_prefix, 
                                        $page_suffix = "", 
                                        $postdata = null, 
                                        $show_title = true, 
                                        $is_get = FALSE, 
                                        $search_query_string = "") {

            # calcuate last page
            $last_page = ceil($max_results / $limit);

            echo "<div 
                    class=\"pagination max-results-{$max_results} page-number-{$page_number} last-page-{$last_page}\" 
                    data-max-results=\"{$max_results}\" 
                    data-page-number=\"{$page_number}\"
                    data-last-page=\"{$last_page}\"
                    data-id=\"pagination\"><ul>";

            if (is_array($postdata)) {
                echo "<form name='pagination_form' method='post'>";
                foreach ($postdata as $key => $value) {
                    echo "<input type='hidden' name='{$key}' value='{$value}'>";
                }
                echo "</form>";

                # building jquery for form submission
                ?>
                <script type='text/javascript'>
                    $(function(){
                        $("div.pagination a").click(function(){
                            var pagination_form = $("div.pagination form[name=pagination_form]");
                            var action_path = $(this).attr("href");

                            pagination_form.attr("action",$(this).attr("href"));

                            var event = jQuery.Event("submit");
                            pagination_form.trigger(event);

                            // This is very important,
                            // Event trigger worked only after I returned false.
                            return false;
                        });


                    });
                </script>
                <?php

            }

            if ($last_page > 1) {
                if ($show_title):
                    echo "<h2>Pages</h2>";
                endif;
                # generate first and previous links
                if ($page_number != 1) {
                    if ($is_get) {
                        echo '<li>';
                        echo anchor( build_link($page_prefix , "{$page_prefix}&page_number=1&limit=" . $limit . "&max_results={$max_results}"), "&laquo; " . _t("first"), "class=\"first left\"");
                        echo "</li>";
                        echo '<li>';
                        echo anchor( build_link($page_prefix ,  "{$page_prefix}&page_number=" . ($page_number - 1) . "&limit=" . $limit . "&max_results={$max_results}") , "&laquo; " . _t("previous"), "class\"prev left\"");
                        echo '</li>';
                    } else {
                        echo '<li>';
                        echo anchor($page_prefix . "1/" . $limit . "/" . $page_suffix, "&laquo; " . ("first"), "class\"first left\"");
                        echo '</li>';
                        echo '<li>';
                        echo anchor($page_prefix . ($page_number - 1) . "/" . $limit . "/" . $page_suffix, "&laquo; " . ("previous"), "class\"prev left\"");
                        echo '</li>';
                    }
                }

                # trim the pagination, currently set to 20 for maximum
                # generate the links

                $trim = array();

                if ($last_page > 20) {

                    $a = 1;
                    $b = 4;

                    $c = $page_number - 4;
                    $d = $page_number;

                    $e = $page_number + 4;
                    $f = $last_page - 4;
                    $g = $last_page;

                    $trim[] = array($a, $b);
                    $trim[] = array($c, $d);
                    $trim[] = array($d, $e);
                    $trim[] = array($f, $g);
                } else {
                    $trim[] = array(1, $last_page);
                }

                $trim[] = array($last_page, $last_page + 1);

                # generate links

                $previous = 1;

                foreach ($trim as $x) {

                    $start = $x[0];
                    $end = $x[1];

                    if ($previous > $start)
                        $start = $previous;

                    if ($end < $start)
                        continue;

                    if ($end < $previous) {
                        continue;
                    }


                    if (($start - $previous) > 1) {
                        echo "<li><a href=#>...</a></li>";
                    }

                    for ($i = $start; $i < $end; $i++) {
                        if ($i > $last_page)
                            continue;
                        if ($page_number == $i) {
                            echo "<li class=\"active\">";
                            if ($is_get) {
                                echo anchor(build_link($page_prefix ,  "{$page_prefix}&page_number=" . $i . "&limit=" . $limit . "&max_results={$max_results}") , $i, "class='number current active'");
                            } else {
                                echo anchor($page_prefix . $i . "/" . $limit . "/" . $page_suffix, $i, "class='number active current'");
                            }
                            echo "</li>";
                        } else {
                            echo "<li>";
                            if ($is_get) {
                                echo anchor( build_link($page_prefix ,  "{$page_prefix}&page_number=" . $i . "&limit=" . $limit . "&max_results={$max_results}") , $i, "class='number'");
                            } else {
                                echo anchor($page_prefix . $i . "/" . $limit . "/" . $page_suffix, $i, "class='number'");
                            }
                            echo "</li>";
                        }
                    }

                    $previous = $end;
                }

                # generate last and next links
                if ($page_number != $last_page) {
                    if ($is_get) {
                        echo '<li>';
                        echo anchor(build_link($page_prefix , "{$page_prefix}&page_number=" . ($page_number + 1) . "&limit=" . $limit . "&max_results={$max_results}" ) , _t("next") . " &raquo;", "class\"next right\"");
                        echo '</li>';
                        echo '<li>';
                        echo anchor(build_link($page_prefix . "{$page_prefix}&page_number=" . ($last_page) . "&limit=" . $limit . "&max_results={$max_results}") , _t("last") . " &raquo;", "class\"last right\"");
                        echo '</li>';
                    } else {
                        echo '<li>';
                        echo anchor($page_prefix . ($page_number + 1) . "/" . $limit . "/" . $page_suffix, ("next") . " &raquo;", "class\"next right\"");
                        echo '</li>';
                        echo '<li>';
                        echo anchor($page_prefix . $last_page . "/" . $limit . "/" . $page_suffix, ("last") . " &raquo;", "class\"last right\"");
                        echo '</li>';
                    }
                }
            }
            echo "</ul></div>";
        }

        /**
         * Returns pagination offset for the limit and page number provided
         *
         * @param mixed $page_number - the page number which starts with the convention of 1 as the first page
         * @param mixed $limit - the number of rows to be returned
         */
        public static function pagination_offset($page_number, $limit) {
            if ($page_number <= 1) {
                $page_number = 1;
            }

            if ($limit < 1)
                $limit = 10;

            $rows = $limit;

            $offset = ($page_number - 1) * $rows;

            return $offset;
        }
	}

    if (!function_exists("pagination_offset")) {
        function pagination_offset($page_number, $limit) {
            return Helper_Pagination::pagination_offset($page_number, $limit);
        }
    }

    /** Some missing functions required by the pagination helper **/
    /** @todo - throw these functions as soon as possible **/

    if(!function_exists("_t")){
    	function _t($string){
    		return $string;
    	}
    }

    if(!function_exists("build_pagination_links")){
        function build_pagination_links($page_number = 1, 
                                            $limit = DB::default_limit, 
                                            $max_results, 
                                            $page_prefix, 
                                            $page_suffix = "", 
                                            $postdata = null, 
                                            $show_title = true, 
                                            $is_get = FALSE, 
                                            $search_query_string = "") {
            return Helper_Pagination::build_pagination_links($page_number , $limit, $max_results, $page_prefix, $page_suffix, $postdata, $show_title, $is_get, $search_query_string);
        }
    }

    if(!function_exists("build_link")){
        function build_link($link1 = "" , $arguments = ""){
            $l1 = parse_url($link1);
            $l2 = parse_url($arguments);

            if(isset($l1["query"])){
                parse_str($l1["query"], $q1);
            }else{
                $q1 = array();
            }

            if(isset($l2["query"])){
                parse_str($l2["query"], $q2);
            }else{
                $q2 = array();
            }

            $query_args = http_build_query(array_merge($q1, $q2));
            $final_url = "{$l1["scheme"]}://{$l1["host"]}/{$l1["path"]}?{$query_args}";
            return $final_url;
        }
    }
