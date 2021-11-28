export function Validator(options) {
  // Na ná F8 ấy

  const form = document.querySelector(options.form);
  let selectorRules = {};

  options.rules.forEach((rule) => {
    let key = rule.selector;
    selectorRules[key] ??= [];
    selectorRules[key].push(rule.isValid);
    let input = rule.input;
    input.onblur = () => validate(input, key);
    input.oninput = () => {
      let groupEl = input.closest(options.groupSelector);
      let messageEl = groupEl.querySelector(options.messageSelector);
      messageEl.innerText = "";
      groupEl.classList.remove("invalid");
    };
  });

  form.addEventListener("submit", async function (e) {
    e.preventDefault();
    const rulesCheck = await Promise.all(
      options.rules.map((rule) => rule.input.onblur())
    );
    const isFormValid = rulesCheck.every((check) => !!check);
    if (!isFormValid) return;
    if (options.onSubmit && typeof options.onSubmit === "function") {
      const data = new FormData(form);
      options.onSubmit(data);
    } else {
      form.submit();
    }
  });

  async function validate(input, selector) {
    let groupEl = input.closest(options.groupSelector);
    let messageEl = groupEl.querySelector(options.messageSelector);
    let errorMessage;
    for (const rule of selectorRules[selector]) {
      errorMessage = await rule();
      if (errorMessage) break;
    }
    if (errorMessage) {
      messageEl.innerText = errorMessage;
      groupEl.classList.add("invalid");
    } else {
      messageEl.innerText = "";
      groupEl.classList.remove("invalid");
    }
    return !errorMessage;
  }
}

Validator.required = function (selector, message) {
  const item = document.querySelector(selector);
  return {
    selector: selector,
    input: item,
    isValid: function () {
      return item.value ? undefined : message;
    },
  };
};

Validator.minLength = function (selector, min, message) {
  const item = document.querySelector(selector);
  return {
    selector: selector,
    input: item,
    isValid: function () {
      return item.value.length >= min ? undefined : message;
    },
  };
};

Validator.regex = function (selector, regex, message) {
  const item = document.querySelector(selector);
  return {
    selector: selector,
    input: item,
    isValid: function () {
      const re = new RegExp(regex);
      return re.test(item.value) ? undefined : message;
    },
  };
};

Validator.ajax = function (selector, message, fn) {
  const item = document.querySelector(selector);
  return {
    selector: selector,
    input: item,
    isValid: async function () {
      return (await fn(item.value)) ? undefined : message;
    },
  };
};

Validator.custom = function (selector, message, fn) {
  const item = document.querySelector(selector);
  return {
    selector: selector,
    input: item,
    isValid: function () {
      return fn(item.value) ? undefined : message;
    },
  };
};
