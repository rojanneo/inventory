<?php

//require_once('system/model.php');
class GenealogyRabbitModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    /* QUERY FOR SINGLE FAMILY */

    public function rabitparent($r_id) {
        $sql = "SELECT * FROM `aa_rabbits` WHERE `r_id`='$r_id'";
        $pdetail = $this->connection->Query($sql);
        if ($pdetail) {
            $pdetail = $pdetail[0];
            return $pdetail;
        } else {
            return false;
        }
    }

    public function rabbitMattinglist() {
        /*
          for($i=1;$i<=15;$i++)
          { //$q="INSERT INTO `_hdc`.`aa_family` (`f_id`, `created_DT`) VALUES ('$i', '2014-11-24')"; $this->connection->InsertQuery($q);
          for($j=1;$j<=3;$j++)
          {
          $query_b="INSERT INTO  `_hdc`.`aa_rabbits` (`r_id` ,`type` ,`l_id` ,`f_id` ,`does_id` ,`buck_id` ,`last_given_birth` ,`status`)
          VALUES (NULL ,  'D', NULL , '$i', NULL , NULL ,  '2014-11-11',  '1')";
          $this->connection->InsertQuery($query_b);
          }
          $query_d="INSERT INTO  `_hdc`.`aa_rabbits` (`r_id` ,`type` ,`l_id` ,`f_id` ,`does_id` ,`buck_id` ,`last_given_birth` ,`status`)
          VALUES ( NULL ,  'B', NULL , '$i', NULL , NULL ,  '2014-11-11',  '1')";
          $this->connection->InsertQuery($query_d);
          }
          die();
         */
        $query = "SELECT DISTINCT `f_id`,`r_id` FROM `aa_rabbits` WHERE `status`='1' AND `type`='B' AND `f_id`!='null'";
        $distinctbuck = $this->connection->Query($query);
        $matinglist = array();
        $i = 0;
        $date = date("Y-m-d");
        $date = date("Y-m-d", strtotime($date . " -14 day"));
        foreach ($distinctbuck as $distinctbuck_id) {
            $family_id = $distinctbuck_id['f_id'];
            $doe_query = "Select `r_id` FROM `aa_rabbits` WHERE `r_id`= IF(
                (SELECT `r_id` FROM `aa_rabbits` WHERE `f_id`=$family_id AND `type`='D' AND `status`='1' AND `last_given_birth` IS NULL LIMIT 1) IS NULL,
                (SELECT `r_id` FROM `aa_rabbits` WHERE `f_id`=$family_id AND `type`='D' AND `status`='1' AND `last_given_birth`>'$date' LIMIT 1),
                (SELECT `r_id` FROM `aa_rabbits` WHERE `f_id`=$family_id AND `type`='D' AND `status`='1' AND `last_given_birth`IS NULL LIMIT 1)
                )";
            $buck = $this->connection->Query($doe_query);
            if ($buck) {
                $matinglist[$i]['doesid'] = $buck[0]['r_id'];
                $matinglist[$i]['fid'] = $distinctbuck_id['f_id'];
                $matinglist[$i]['buckid'] = $distinctbuck_id['r_id'];
            }
            $i++;
        }
        $apple = array();
        $apple[1]['buck_id'] = 1;
        $apple[1]['does_id'] = 2;
        //$a=$this->recurseDB($apple); 
        //var_dump($a);  die(); 
        return $matinglist;
    }

// try123
    public function recurseDB($last_level, &$result, $level = 1, $limit = 2) {
        //Check if las level was an array
        if ($level <= $limit) {
            if (is_array($last_level)) {
                foreach ($last_level as $array) {
                    $male_p = $this->connection->Query("SELECT * FROM aa_rabbits WHERE r_id='{$array['buck_id']}'");
                    $female_p = $this->connection->Query("SELECT * FROM aa_rabbits WHERE r_id='{$array['does_id']}'");
                    $result[$level + 1][] = $male_p[0];
                    $result[$level + 1][] = $female_p[0];
                    //$result[$level+1][] = $this->fetchRow("pigeon_SN='{$array['SN_m']}'");
                    //$result[$level+1][] = $this->fetchRow("pigeon_SN='{$array['SN_t']}'");
                }
                $this->recurseDB($result[$level + 1], $result, $level + 1, $limit);
            } else {
                $male_pp = $this->connection->Query("SELECT * FROM aa_rabbits WHERE r_id='{$last_level['buck_id']}'");
                $female_pp = $this->connection->Query("SELECT * FROM aa_rabbits WHERE r_id='{$last_level['does_id']}'");
                $result[$level][] = $male_pp[0];
                $result[$level][] = $female_pp[0];
                //$result[$level][] = $this->fetchRow("pigeon_SN='{$last_level['buck_id']}'");
                //$result[$level][] = $this->fetchRow("pigeon_SN='{$last_level['does_id']}'");
            }
        }
    }

//try123 

    /*
      public function rabbitHaveNotSameParent($x_parent_MF,$y_parent_MF)
      {
      if(($x_parent_MF['buck_id']==0) || ($x_parent_MF['buck_id']==null))   { $x_parent_MF['buck_id']=-1; }
      if(($x_parent_MF['does_id']==0) || ($x_parent_MF['does_id']==null))   { $x_parent_MF['does_id']=-2; }
      if(($y_parent_MF['buck_id']==0) || ($y_parent_MF['buck_id']==null))   { $y_parent_MF['buck_id']=-3; }
      if(($y_parent_MF['does_id']==0) || ($y_parent_MF['does_id']==null))   { $y_parent_MF['does_id']=-4; }
      if(($x_parent_MF['buck_id']==$y_parent_MF['buck_id'])||($x_parent_MF['does_id']==$y_parent_MF['does_id']))
      {
      return false;
      }
      else
      {
      return true;
      }

      }
     */

    public function rabbitmate($x, $y) {
        $xinformation = $this->rabitparent($x);
        if (empty($xinformation)) {
            return'No rabbit with ID =' . $x . ' exsit';
        } else {
            $xDetail = array();
            $xDetail['buck_id'] = $xinformation['buck_id'];
            $xDetail['does_id'] = $xinformation['does_id'];
        }
        $yinformation = $this->rabitparent($y);
        if (empty($yinformation)) {
            return'No rabbit with ID =' . $y . ' exsit';
        } else {
            $yDetail = array();
            $yDetail['buck_id'] = $yinformation['buck_id'];
            $yDetail['does_id'] = $yinformation['does_id'];
        }
        //$xDetail=$this->malefemalerabbitparent($x); if(empty($xDetail))  { return'No rabbit with ID ='.$x.' exit'; }
        //$yDetail=$this->malefemalerabbitparent($y); if(empty($yDetail))  { return'No rabbit with ID ='.$y.' exit'; }
        if ($xinformation['type'] == $yinformation['type']) {
            return'Both Have Same Gender';
        }
        if ($xinformation['status'] == 0) {
            return'Sorry Rabbit with ID' . $x . 'Status is 0. It either mean this rabbit is death or is already sold as a product';
        }
        if ($yinformation['status'] == 0) {
            return'Sorry Rabbit with ID' . $y . 'Status is 0. It either mean this rabbit is death or is already sold as a product';
        }
        $xdobsql = 'SELECT `DOB` FROM `aa_litter` WHERE `l_id`="' . $xinformation["l_id"] . '"';
        $xdobsql = $this->connection->Query($xdobsql);
        $ydobsql = 'SELECT `DOB` FROM `aa_litter` WHERE `l_id`="' . $yinformation["l_id"] . '"';
        $ydobsql = $this->connection->Query($ydobsql);   // var_dump($ydobsql[0]); var_dump($xdobsql[0]); die();
        if (isset($ydobsql[0]) and $xdobsql[0]) {
            if ($ydobsql[0]['DOB'] != $xdobsql[0]['DOB']) {
                return 'Sorry Rabbit arenot Borned at same Date. Its againsts our policy to mate rabbit with different age';
            }
        }
        if (($xDetail['buck_id'] == 0) && ($xDetail['does_id'] == 0) || ($yDetail['buck_id'] == null) && ($yDetail['does_id'] == null)) { // If the parent is null then they can mate with anyone
            return 'Yes They Can Mate';
        }
        $xinfo = $this->recursionparentfinder($xDetail, 7);
        $yinfo = $this->recursionparentfinder($yDetail, 7);
        $intersectionvalue = $this->intersectioncheck($xinfo, $yinfo);
        if (empty($intersectionvalue)) {
            return'Yes This Rabbit Can Be Mated';
        } else {
            $valuereturn = 'They Cannt Be Mate As They Have Following Parent With same id';
            foreach ($intersectionvalue as $intersectionvalue) {
                $valuereturn.='</br>' . $intersectionvalue;
            }
            return $valuereturn;
        }
    }

    public function malefemalerabbitparent($r_id) {
        $query = "SELECT `does_id`,`buck_id` FROM `aa_rabbits` WHERE `r_id`='$r_id'";
        $pdetail = $this->connection->Query($query);
        if ($pdetail) {
            $pdetail = $pdetail[0];
            return $pdetail;
        } else {
            return false;
        }
    }

    public function recursionparentfinder($xDetail, $generationupto) {
        /* $q=1;
          for($s=285;$s<=349;$s++)
          {
          $t=$s+ $q; $q++;
          $u=$t+ 1;
          $sql="INSERT INTO `aa_rabbits`(`r_id`, `type`, `l_id`, `f_id`, `does_id`, `buck_id`, `last_given_birth`, `status`)
          VALUES ('$s','D',null,null,'$t','$u','2014-11-13','1')";
          $this->connection->InsertQuery($sql);
          }
          die();
         */
        $xmaleparents = array();
        $xfemaleparents = array();
        $parents = array();
        $xmf = array();
        $j = 2;
        $k = 1;

        if ($xDetail['buck_id']) {
            $xmaleparents[1] = $xDetail['buck_id'];
        }
        if ($xDetail['does_id']) {
            $xfemaleparents[1] = $xDetail['does_id'];
        }
        for ($i = 3; $i < $generationupto; $i++) {
            unset($xmf);
            foreach ($xDetail as $xinfoLoop) {
                $mf = $this->malefemalerabbitparent($xinfoLoop);
                if ($mf) {
                    if ((isset($mf['buck_id'])) || ($mf['buck_id'] != 0)) {
                        $xmaleparents[$j] = $mf['buck_id'];
                        $xmf[$k] = $mf['buck_id'];
                        $k++;
                    }
                    if ((isset($mf['does_id'])) || ($mf['does_id'] != 0)) {
                        $xfemaleparents[$j] = $mf['does_id'];
                        $xmf[$k] = $mf['does_id'];
                        $k++;
                    }
                    $j++;
                }
            }
            if (isset($xmf)) {
                unset($xDetail);
                $xDetail = $xmf;
            }
        }
        $parents['male'] = $xmaleparents;
        $parents['female'] = $xfemaleparents;
        if ($parents) {
            return $parents;
        } else {
            return false;
        }
    }

    public function intersectioncheck($xinfo, $yinfo) {
        $k = 1;
        $intersectedarrayvalue = array();
        foreach ($xinfo['male'] as $xmale) {
            foreach ($yinfo['male'] as $ymale) {
                $intersectedvalue = $this->checkifmatch($xmale, $ymale);
                if ($intersectedvalue) {
                    $intersectedarrayvalue[$k] = $intersectedvalue;
                    $k++;
                }
            }
        }

        foreach ($xinfo['female'] as $xfemale) {
            foreach ($yinfo['female'] as $yfemale) {
                //echo $yfemale; echo $xfemale; 
                $intersectedvalue = $this->checkifmatch($xfemale, $yfemale);
                if ($intersectedvalue) {
                    $intersectedarrayvalue[$k] = $intersectedvalue;
                    $k++;
                }
            }
        }
        return $intersectedarrayvalue;
    }

    public function checkifmatch($x, $y) {
        if ($x == $y) {
            return $x;
        } else
            return false;
    }

    public function insertrabbit($post_data) { //mysql_real_escape_string
        if (isset($post_data))
            extract($post_data);

        $query = 'INSERT INTO `aa_rabbits`(`type`, `l_id`, `f_id`, `does_id`, `buck_id`, `last_given_birth`, `status`,`rabbit_slug`)
          VALUES ("' . $rtype . '","' . $l_Id . '","' . $f_ID . '","' . $p_doe_Id . '","' . $p_buck_Id . '","' . $b_date . '","' . $rstatus . '","' . $rslug . '")';
        $sql = $this->connection->InsertQuery($query);
        if ($sql) {
            $insert_Id = $this->connection->GetInsertID();

            if ($rtype == 'B') {
                $this->insertintofamilytobe($insert_Id, 'D');
                /*
                  var_dump('boy');
                  $queryForAll_D="SELECT r_id FROM `aa_rabbits` WHERE `type`='D'";
                  $sqlforall_D=$this->connection->Query($queryForAll_D);
                  if($sqlforall_D)
                  {
                  foreach ($sqlforall_D as $sqlforall_D_id)
                  {
                  echo'<pre>';
                  var_dump($sqlforall_D_id['r_id']);
                  $value=$this->comparevalue($sqlforall_D_id['r_id'],$insert_Id);
                  if($value)
                  {
                  echo 'yes';
                  $sqlfamlytobe='INSERT INTO `aa_family_to_be`(`buck_r_id`, `doe_r_id`) VALUES ('.$insert_Id.','.$sqlforall_D_id['r_id'].')';
                  $this->connection->InsertQuery($sqlfamlytobe);
                  }
                  else { echo'no';}
                  echo'</pre>';
                  }
                  }
                 */

                //echo'<pre>'; var_dump($sqlforall_D); echo'</pre>'; die();
            } else {
                $this->insertintofamilytobe($insert_Id, 'B');
                var_dump('girl');
            }
            return true;
        } else {
            return false;
        }
    }

    public function insertintofamilytobe($insert_Id, $type) {
        $queryForAll_D = 'SELECT r_id FROM `aa_rabbits` WHERE `type`="' . $type . '"';
        $sqlforall_D = $this->connection->Query($queryForAll_D);
        if ($sqlforall_D) {
            foreach ($sqlforall_D as $sqlforall_D_id) {
                $value = $this->comparevalue($sqlforall_D_id['r_id'], $insert_Id);
                if ($value) {
                    $sqlfamlytobe = 'INSERT INTO `aa_family_to_be`(`buck_r_id`, `doe_r_id`) VALUES (' . $insert_Id . ',' . $sqlforall_D_id['r_id'] . ')';
                    $this->connection->InsertQuery($sqlfamlytobe);
                }
            }
        }
    }

    public function getfamilyid() {
        $query = 'SELECT `f_id` FROM `aa_family`';
        $sql = $this->connection->Query($query);
        if ($sql) {
            return $sql;
        } else
            return false;
    }

    public function rabbitelement($element, $table, $comparevalue, $comparecolumn) {
        if (empty($comparevalue) && empty($comparecolumn)) {
            $query = 'SELECT `' . $element . '` FROM `' . $table . '`';
        } else {
            $query = 'SELECT `' . $element . '` FROM `' . $table . '` WHERE `' . $comparecolumn . '`="' . $comparevalue . '" ';
        }
        $sql = $this->connection->Query($query);
        if ($sql) {
            return $sql;
        } else
            return false;
    }

    public function comparevalue($does_id, $buck_id) {
        $xinformation = $this->rabitparent($does_id);
        if (empty($xinformation)) {
            return false;
        } else {
            $xDetail = array();
            $xDetail['buck_id'] = $xinformation['buck_id'];
            $xDetail['does_id'] = $xinformation['does_id'];
        }
        $yinformation = $this->rabitparent($buck_id);
        if (empty($yinformation)) {
            return false;
        } else {
            $yDetail = array();
            $yDetail['buck_id'] = $yinformation['buck_id'];
            $yDetail['does_id'] = $yinformation['does_id'];
        }
        if (($xDetail['buck_id'] == 0) && ($xDetail['does_id'] == 0) || ($yDetail['buck_id'] == null) && ($yDetail['does_id'] == null)) { // If the parent is null then they can mate with anyone
            return true;
        }
        $xinfo = $this->recursionparentfinder($xDetail, 7);
        $yinfo = $this->recursionparentfinder($yDetail, 7);
        $intersectionvalue = $this->intersectioncheck($xinfo, $yinfo);
        if (empty($intersectionvalue)) {
            return true;
        } else {
            return false;
        }
    }

}
