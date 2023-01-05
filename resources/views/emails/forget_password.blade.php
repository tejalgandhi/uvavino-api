Hello {{ $user->username }},

To reset the password click on the following link {{ $reset_url . $token }}