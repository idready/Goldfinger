<?php 

   class Avatar {


        public function get_avatar($user_id) 
        {

            // check folder
            // check if avatar exist
                // if yes return the path (absolute?)
                // else return the default avatar
            return null;
        }

        public function change_avatar ($user_id) 
        {

            // check if user has avatar
                // if yes change if
                // if not create folder and add avatar
            return null;
        }

        public function has_avatar($user_id)
        {
            // check folder then avatar
            return false;
        }

   }

?>