export interface ShowBoardResponseDto {
  id: number;
  user_id: number;
  text: string;
  created_at: Date;
  updated_at: Date;
  post_tags: Array<{
    id: number;
    tag_name: string;
    created_at: Date;
    updated_at: Date;
    tag: Array<{
      id: number;
      tag_name: string;
      created_at: Date;
      updated_at: Date;
    }>;
  }>;
  imagetag: Array<{
    id: number;
    post_id: number;
    img_url: string;
    created_at: Date;
    updated_at: Date;
  }>;
}
