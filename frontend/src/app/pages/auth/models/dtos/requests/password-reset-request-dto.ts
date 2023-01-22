export interface PasswordResetRequestDto {
  token: string,
  password: string,
  password_confirmation: string
}
