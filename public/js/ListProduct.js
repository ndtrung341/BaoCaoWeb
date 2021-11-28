/// <reference path="./jquery-3.6.0.js" />

import { formatNumber } from './HandleCart.js';

$(document).ready(() => {
	$('.filter-title').click(function () {
		$(this).parent().find('.filter-content').slideToggle(300);
		$(this).toggleClass('expand');
	});

	$('#price-min').on('input', function (e) {
		const min = this.value;
		const max = $('#price-max').val();
		if (max - min <= 0) $('#price-min').val(max);
		fill();
	});

	$('#price-max').on('input', function (e) {
		const min = $('#price-min').val();
		const max = this.value;
		if (max - min <= 0) $('#price-max').val(min);
		fill();
	});

	const fill = () => {
		$('#label__price--max').text(
			formatNumber($('#price-max').val() * 1) + '₫',
		);
		$('#label__price--min').text(
			formatNumber($('#price-min').val() * 1) + '₫',
		);
		const left = ($('#price-min').val() / $('#price-min').attr('max')) * 100;
		const right = ($('#price-max').val() / $('#price-min').attr('max')) * 100;
		$('.slider-track').css({
			background: `linear-gradient(to right, #ccc ${left}% , darkorange ${left}% , darkorange ${right}%, #ccc ${right}%)`,
		});
	};

	// function formatNumber(number) {
	// 	return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
	// }

	$(function () {
		// let options = getUrlParams(); // Biến lưu những giá trị cần lọc
		let options = getSearchParams();
		let totalPages = 2;
		let isInfiniteScroll = false;
		/**
		 * LẤY GIÁ TRỊ CHECKBOX CẦN LỌC
		 */
		$('.filter-item input[type=checkbox]').change(function () {
			delete options['page'];
			if (this.checked) {
				// checkbox được tích thì lưu vào
				options.filter ??= {};
				if (!Array.isArray(options.filter[this.name]))
					options.filter[this.name] = [];
				options.filter[this.name].push(this.value);
			} else {
				// checkbox ko dc tích thì xóa khỏi mảng
				options.filter[this.name].splice(
					options.filter[this.name].indexOf(this.value),
					1,
				);
				if (options.filter[this.name].length == 0)
					delete options.filter[this.name]; // xóa dk lọc nếu ko còn giá trị nào cả
			}
			setSearchParams();
			paginate(window.location.href);
		});

		/**
		 * LỌC GIÁ SẢN PHẨM
		 */
		$('.filter_price').click(function () {
			delete options['page'];
			options.filter ??= {};
			options.filter['maxPrice'] = $('#price-max').val();
			options.filter['minPrice'] = $('#price-min').val();
			setSearchParams();
			paginate();
		});

		// Reset filter
		$('.btn-reset').on('click', function () {
			delete options.filter;
			delete options.page;
			// Uncheck tất cả checkbox
			$('input[type=checkbox]').each(function () {
				$(this).prop('checked', false);
			});
			// Đặt giá lại như cũ
			$('#price-min').val($('#price-min').attr('min'));
			$('#price-max').val($('#price-max').attr('max'));
			fill();

			$('.summary').show();
			if (!isInfiniteScroll) $('.pagination').show();
			setSearchParams();
			paginate();
		});

		/**
		 * Sắp xếp theo lựa chọn
		 */
		$('.sort').on('change', function () {
			delete options['page'];
			if ($(this).val() == 'default') {
				delete options['sort_key'];
				delete options['sort_order'];
			} else [options['sort_key'], options['sort_order']] = this.value.split('_');

			$('.sort-selected').text($(this).find('option:selected').text());

			setSearchParams();
			paginate();
		});

		function flattenObject(obj) {
			return Object.entries(obj).reduce((acc, [key, val]) => {
				if (typeof val === 'object' && !Array.isArray(val))
					acc = { ...acc, ...flattenObject(val) };
				else acc[key] = val;
				return acc;
			}, {});
		}

		function setSearchParams() {
			const flatten = flattenObject(options);
			const params = new URLSearchParams(flatten);
			const url = new URL(window.location);
			url.search = decodeURIComponent(params.toString());
			const searchString = url.search;
			if (isInfiniteScroll) url.searchParams.delete('page');
			console.log(url.href);
			window.history.pushState({}, '', decodeURIComponent(url.href));
			console.log(searchString);
			return searchString;
		}

		function getSearchParams() {
			const params = new URLSearchParams(window.location.search);

			let temp = [...params.entries()].reduce((acc, [key, val]) => {
				const element = $(`input[name=${key}]`);
				if ($(element).attr('type') == 'checkbox') {
					acc.filter ??= {};
					let values = val.split(',');
					acc.filter[key] = values;
					values.forEach((val) =>
						$(`[name=${key}][value=${val}]`).attr('checked', 'checked'),
					);
				} else acc[key] = val;
				return acc;
			}, {});
			console.log(temp);

			if (temp['sort_key'] && temp['sort_order']) {
				const value = temp['sort_key'] + '_' + temp['sort_order'];
				$('.sort').each(function () {
					$(this).val(value);
				});
				$('.sort-selected').text($('#sort option:selected').text());
			}
			return temp;
		}

		$('.pagination').on('click', function (e) {
			e.preventDefault();
			const target = e.target.closest('[data-page]');
			if (!target) return;
			// Lấy số trang đã chọn
			const page = $(target).attr('data-page');
			console.log(page);
			if (page == 1) delete options.page;
			else options.page = page;
			setSearchParams();
			// Phân trang
			paginate();
		});

		async function fetchProduct(url) {
			// Gửi yêu cầu
			const res = await fetch(url, {
				method: 'POST',
			});
			const data = await res.json();
			totalPages = data.paginate.totalPages;
			return data;
		}

		function renderProducts(html, clear = true) {
			const element = new DOMParser().parseFromString(html, 'text/html');
			if (!clear) {
				$('.products').append($(element).find('.products').html());
			} else {
				$('.pagination').html($(element).find('.pagination').html());
				setTimeout(
					() =>
						$('.products-wrapper').html(
							$(element).find('.products-wrapper').html(),
						),
					1000,
				);
			}
		}

		async function paginate() {
			window.scroll({
				top: 0,
			});
			$('.products-wrapper').html('<div class="loader"></div>');
			const data = await fetchProduct(window.location.href);
			console.log(data);
			if (data.paginate.totalItems == 0) {
				console.log(123);
				$('.summary').hide();
				$('.products-wrapper').html(
					'<p style="font-size:1.6rem;text-align:center;">Không có sản phẩm nào. Bạn thử tắt điều kiện lọc và tìm lại nhé?</p>',
				);
				$('.pagination').hide();
				return;
			}
			renderProducts(data.html);
			if (isInfiniteScroll)
				// Kích hoạt sự kiện cuộn nếu có
				window.addEventListener('scroll', infiniteScroll);
			// Hiển thị/Ẩn nút show all
			if (data.paginate.totalPages == 1 || isInfiniteScroll)
				$('.btn-show').hide();
			else $('.btn-show').show();
			// Hiển thị số lượng
			$('.show').text(
				`Showing ${data.paginate.start} - ${data.paginate.end} of ${data.paginate.totalItems} result`,
			);
		}

		const productsWrapper = document.querySelector('.products-wrapper');
		const infiniteScroll = async () => {
			// Vị trí cuộn
			const positionScroll =
				document.documentElement.scrollTop +
				document.documentElement.clientHeight;
			// Vị trí để kich hoạt event
			const positionEnd =
				productsWrapper.clientHeight + productsWrapper.offsetTop;
			// Lấy số trang hiện tại
			options.page ??= 1;
			// Cuộn tới vị trí chỉ định
			if (positionScroll > positionEnd + 80) {
				// Tạm dừng sự kiện scroll để tránh bị chạy nhiều lần
				window.removeEventListener('scroll', infiniteScroll);
				if (options.page >= totalPages) return;
				await wait(500);
				// Tăng số trang lên
				options.page += 1;
				// Hiển thị thông báo đang tải sản phẩm
				$('.loader-products').show();
				const data = await fetchProduct(
					window.location.pathname + setSearchParams(),
				);
				// Đợi 1s, sau đó render trang
				await wait(1000);
				renderProducts(data.html, false);
				// Hiển thị số lượng
				$('.show').text(
					`Showing 1 - ${data.paginate.end} of ${data.paginate.totalItems} result`,
				);
				// Ẩn thông báo
				$('.loader-products').hide();
				// Nếu số trang chưa vượt quá tổng số trang cho phép thì kích hoạt lại sự kiện cuộn
				if (options.page < totalPages)
					window.addEventListener('scroll', infiniteScroll);
			}
		};

		// Xử lý nút kích hoạt sự kiện cuộn trang
		$('.btn-show').on('click', async function () {
			isInfiniteScroll = true;
			// Xóa nút
			$(this).remove();
			// Xóa thanh phân trang
			$('.pagination').remove();
			// Nếu page hiện tại khác 1
			if (options.page) {
				delete options.page; // reset page về 1
				const data = await fetchProduct(
					window.location.pathname + setSearchParams(),
				);
				renderProducts(data.html);
			}
			// Kích hoạt sự kiện ngay lập tức
			infiniteScroll();
		});
	});

	function wait(milliseconds) {
		return new Promise((resolve) => setTimeout(resolve, milliseconds));
	}
});
