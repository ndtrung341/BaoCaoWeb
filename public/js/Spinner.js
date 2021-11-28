function spinnerInput(options) {
  const container = document.querySelector(options.container); // Khung chứa nút và input
  const btnIncrease = container?.querySelector(options.btnIncrease); // Nút tăng
  const btnDecrease = container?.querySelector(options.btnDecrease); // Nút giảm
  const input = container?.querySelector(options.input); // Input
  const min = options.min; // Giá trị min ( min: Cố định; onMin(): Không cố định)
  function getMax() {
    if (options.onMax && typeof options.onMax == "function") {
      return options.onMax(); // Không cố định
    } else {
      return options.max; // Cố định
    }
  }
  /**
   * ================
   * XỬ LÝ NÚT TĂNG
   * ================
   */
  btnIncrease?.addEventListener("click", () => {
    const max = getMax();
    if (1 * input.value + 1 > max) return;
    input.value = ++input.value;
    if (options.onChange) options.onChange(input.value);
  });

  /**
   * ================
   * XỬ LÝ NÚT GIẢM
   * ================
   */
  btnDecrease?.addEventListener("click", () => {
    if (1 * input.value - 1 < min) return;
    input.value = --input.value;
    if (options.onChange) options.onChange(input.value);
  });

  /**
   * ===========================================
   * KIỂM TRA ĐẦU VÀO
   * Không cho nhập kí tự nào ngoại trừ số
   * Không cho phép nhập số 0 ở đầu (Chưa làm nữa)
   * ============================================
   */
  input?.addEventListener("keypress", (e) => {
    if (isNaN(e.key) || (input.value.length == 0 && e.key == "0"))
      e.preventDefault();
  });
  /**
   * =========================
   * XỬ LÝ KHI NHẬP INPUT
   * =========================
   */
  input?.addEventListener("input", () => {
    const max = getMax();
    input.value > max && (input.value = max || 1);
    if (options.onChange) options.onChange(input.value);
  });

  // XỬ LÝ KHI NGƯỜI DÙNG NHẤN RA NGOÀI INPUT
  input?.addEventListener("blur", () => !input.value && (input.value = min));
}
