<?php

namespace App\Http\Controllers;

use App;

class TestController extends Controller
{
    public function getPosts() {
        $me = 'your_profile_id';
        $token = 'your_access_token';

        $fb = new \Facebook\Facebook([
            'app_id' => 'your_app_id',
            'app_secret' => 'your_app_secret',
            'default_graph_version' => 'v2.8',
            //'default_access_token' => '{access-token}', // optional
        ]);

        try {
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $response = $fb->get($me . '/posts/?limit=100&fields=comments.summary(true),likes.summary(true)', $token);
            $value = json_decode($response->getBody());

            foreach ($value->data as $each_data) {
                $comments_data[] = $each_data->comments;
                $likes_data[] = $each_data->likes;
            }

            foreach ($comments_data as $each_comment) {
                $comments_data_full[] = $each_comment->data;
            }

            foreach ($likes_data as $each_like) {
                $likes_data_full[] = $each_like->data;
            }

            foreach ($comments_data_full as $each_comment) {
                if (!is_null($each_comment)) {
                    foreach ($each_comment as $ec) {
                        $comments_data_abc[] = $ec->from->id;
                    }
                }
            }

            foreach ($likes_data_full as $each_like) {
                if (!is_null($each_like)) {
                    foreach ($each_like as $el) {
                        $likes_data_abc[] = $el->id;
                    }
                }
            }

            $best_friend = array_merge($likes_data_abc, $comments_data_abc, $comments_data_abc, $comments_data_abc);
            $best_friend_final = array_count_values($best_friend);


            arsort($best_friend_final);

            $my_best_friend_list[] = array_keys($best_friend_final);



            if ($my_best_friend_list[0][0] === $me) {
                $my_final_best_friend = $my_best_friend_list[0][1];
            }
            else {
                $my_final_best_friend = $my_best_friend_list[0][1];
            }

        } catch (\Facebook\Exceptions\FacebookResponseException $e) {

            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $pro_pic = 'https://graph.facebook.com/' . $my_final_best_friend . '/picture?type=large';
        $response_new = $fb->get($my_final_best_friend, $token);
        $name = json_decode($response_new->getBody())->name;

        $pro_url = 'https://facebook.com/' . $my_final_best_friend;

        return view('welcome', [
            'name' => $name,
            'pro_url' => $pro_url,
            'pro_pic' => $pro_pic
        ]);
    }



}