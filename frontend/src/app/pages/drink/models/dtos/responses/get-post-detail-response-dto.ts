export interface GetPostDetailResponseDTO {
  id: number;
  user_id: number;
  text: string;
  created_at: Date;
  user: {
    id: number;
    user_name: string;
    email: string;
  };
  post_tags: {
    id: number;
    post_id: number;
    tag_id: number;
    tag: {
      id: number;
      tag_name: string;
    };
  };
  image: {
    id: number;
    post_id: number;
    img_url: string;
  };
}
