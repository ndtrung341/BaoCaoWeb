import { Validator } from "./Validator.js";

Validator({
  form: "#customer-form",
  groupSelector: ".form-group",
  messageSelector: ".form-message",
  rules: [
    Validator.required("#name", "Họ tên là bắt buộc"),
    Validator.regex("#name", "^[a-zA-Z]+( [a-zA-Z]+)+$", "Họ tên không hợp lệ"), //Không nhập dấu được
    Validator.required("#address", "Địa chỉ là bắt buộc"),
    Validator.required("#phone", "Số điện thoại là bắt buộc"),
    Validator.required("#email", "Email là bắt buộc"),
    Validator.regex(
      "#email",
      "^\\w+([.-]?w+)*@\\w+([.-]?\\w+)*(.\\w{2,3})+$",
      "Email không hợp lệ"
    ),
  ],
  onSubmit: function (data) {
    // Thực hiện khi form submit
    // Lấy thông tin phương thức thanh toán
    const payment = document.querySelector('[name="payment"]:checked').value;
    data.append("payment", payment);

    order(data);
  },
});

function order(data) {
  return fetch("/mvc/checkout/order/", {
    method: "POST",
    body: data,
  })
    .then((res) => {
      if (!res.ok) throw Error(res.statusText);
      return res.json();
    })
    .then((data) => {
      Swal.fire({
        icon: "success",
        title: data.message,
      }).then(() => window.location.replace("/mvc/"));
    })
    .catch((err) => console.error(err));
}
