<?php 

class AMF  {

      private static function getclass($classname) {
        $myclass = new stdClass();
        
        if($classname){
          $myclass->_explicitType = $classname;
        }

        // if($classname == '') {
        //   return new stdClass();
        // }
        // // echo $classname;
        // $arr = explode('\\',$classname);
        // if(count($arr) == 2 )  {
        //     if ( ! class_exists($classname) ) {
        //        // echo "namespace $arr[0]{ class $arr[1]{  public  \$_explicitType = '$arr[0].$arr[1]';  } }";
        //        // exit(); 
        //        eval("namespace $arr[0]{ class $arr[1]{  public  \$_explicitType = '$arr[0].$arr[1]';  } }");
        //     }
        //     $myclass = new $classname();


        // } else if( count($arr) == 1) {

        //     if ( ! class_exists($classname) ) {
        //        eval(" class $classname{} ");
        //     }
        //     $myclass = new $classname();

        // }  else {
        //      $myclass = new stdClass();
        // }
        return $myclass;
      }

      private static function Eloguent2Array($eloquent) {
             return $eloquent->toArray();
      }

      private static function collection2Array($collation) {
              $arrvals = [];
                foreach ($collation as $object) {
                   $arrvals[] = $object;
                }
                return $arrvals;
        }

        public static  function  encode($data,$classtype=0) { 
            if (count($data) == 0) 
                return $data; 
             
            $ret = array(); 
            $substract = false; 
             

            if( $data instanceof  Illuminate\Database\Eloquent\Collection  ) { 
                $data = self::collection2Array($data);
                // echo 'is collection';
             } 
             
            if (! array_key_exists('0', $data)) { 
                $data = array($data); 
                $substract = true; 
            } 
             
             if(is_object($data)) {
                 $data = self::Eloguent2Array($data);
                 // echo 'is object';
             }

             // if(is_array($data)){
             //    echo 'is Array';
             // }


            for ($i=0; $i<count($data); $i++) { 
                if($classtype == 1 ) {
                            $o = new stdClass();
                            if(is_object($data[$i])) {
                               $data[$i] = self::Eloguent2Array($data[$i]);
                             } 
                } else {
                     if(is_object($data[$i])) {
                     
                          $classname = $data[$i]->class;
                          if(!$classname) {
                               $classname = get_class($data[$i]);
                               if($classname){
                                  $o = new stdClass();
                                  $o->_explicitType = $classname;
                               } else {
                                  $o = new stdClass();
                               }
                          } else {
                                $o = self::getclass( $classname );
                          } 

                            $data[$i] = self::Eloguent2Array($data[$i]);
                     
                     } else {
                            $o = new stdClass();
                     }
                }
                foreach ($data[$i] as $property => $value) { 
                        if($value instanceof  Illuminate\Database\Eloquent\Collection  ) { 
                             $value = self::collection2Array($value);
                             $o->{$property} = self::encode($value);
                         } else {
                             $o->{$property} = $value;
                         } 
                } 
                $ret[] = $o; 
            } 
            if ($substract) 
                $ret = $ret[0]; 
            return $ret; 
        } 
}
