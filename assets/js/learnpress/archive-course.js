document.querySelectorAll('.filter-select').forEach(select => {
    const adjustWidth = () => {
        // Tạo một thẻ span ảo để đo độ dài thật của chữ đang chọn
        const tempSpan = document.createElement('span');
        tempSpan.style.visibility = 'hidden';
        tempSpan.style.position = 'absolute';
        tempSpan.style.whiteSpace = 'pre';
        tempSpan.style.fontSize = window.getComputedStyle(select).fontSize;
        tempSpan.style.fontFamily = window.getComputedStyle(select).fontFamily;
        tempSpan.style.fontWeight = window.getComputedStyle(select).fontWeight;
        
        // Lấy chữ của option đang được chọn + thêm chút khoảng trống cho mũi tên
        tempSpan.innerText = select.options[select.selectedIndex].text;
        document.body.appendChild(tempSpan);
        
        // Gán lại chiều rộng cho select (cộng thêm 25px cho cái icon mũi tên góc phải)
        select.style.width = (tempSpan.getBoundingClientRect().width + 25) + 'px';
        
        document.body.removeChild(tempSpan);
    };

    // Chạy ngay khi tải trang và mỗi khi người dùng thay đổi lựa chọn
    adjustWidth();
    select.addEventListener('change', adjustWidth);
});
