export interface ShowUserInfoResponseDTO {
  follow_flg: boolean;
  followed_num: number;
  followee_num: number;
  user_info: {
    id: number;
    user_name: string;
    email: string;
    created_at: Date;
    updated_at: Date;
    posts: [
      {
        id: number;
        user_id: number;
        text: string;
        created_at: Date;
        post_tags: [
          {
            id: number;
            post_id: number;
            tag_id: number;
            tag: {
              id: number;
              tag_name: string;
            }
          }
        ];
        images: [
          {
            id: number;
            post_id: number;
            img_url: string;
          }
        ]
      }
    ]
  };
}
