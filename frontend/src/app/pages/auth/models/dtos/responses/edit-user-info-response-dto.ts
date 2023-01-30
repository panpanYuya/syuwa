export interface EditUserInfoResponseDto {
  result: boolean;
  User: {
    id: number;
    user_name: string;
    email: string;
  }
}
