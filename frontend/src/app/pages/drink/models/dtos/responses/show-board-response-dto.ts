export interface ShowBoardResponseDto {
  id: number;
  user_id: number;
  text: string;
  created_at: Date;
  updated_at: Date;
  post_tags: {
    id: number;
    tag_id: number;
    created_at: Date;
    updated_at: Date;
    tag: {
      id: number;
      tag_name: string;
      created_at: Date;
      updated_at: Date;
    };
  };
  imagetag: {
    id: number;
    post_id: number;
    img_url: string;
    created_at: Date;
    updated_at: Date;
  };
}
