import { Validator } from "./Validator.js";

window.addEventListener("DOMContentLoaded", (e) => {
  Validator({
    form: "#login",
    groupSelector: ".form-group",
    messageSelector: ".form-message",
    rules: [
      Validator.required("#username", "Tên đăng nhập là bắt buộc"),
      Validator.required("#password", "Mật khẩu là bắt buộc"),
    ],
  });
});
