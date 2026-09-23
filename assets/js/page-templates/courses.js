document.addEventListener('DOMContentLoaded', function() {
    const filterItems = document.querySelectorAll('#iddi-ajax-filter-list .filter-item');
    const authorItems = document.querySelectorAll('.author-item');
    const searchForm = document.getElementById('iddi-ajax-search-form');
    const searchInput = document.getElementById('iddi-ajax-search-input');
    const resultContainer = document.querySelector('#iddi-ajax-course-result .iddi-courses__online-tutorial-courses-list');
    
    // Biến lưu trữ trạng thái lọc
    let selectedAuthorId = 0;

    function fetchCourses() {
        // Lấy filter active (không bao gồm dropdown container)
        const activeFilterElement = document.querySelector('#iddi-ajax-filter-list .filter-item.active:not(.iddi-author-dropdown-container)');
        const activeFilter = activeFilterElement ? activeFilterElement.dataset.filter : 'all';
        const searchValue = searchInput.value;

        // Hiệu ứng loading
        resultContainer.style.opacity = '0.5';

        const formData = new FormData();
        formData.append('action', 'iddi_filter_courses');
        formData.append('filter', activeFilter);
        formData.append('search', searchValue);
        formData.append('author_id', selectedAuthorId); // Gửi thêm ID tác giả

        fetch(iddi_vars.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            resultContainer.innerHTML = data;
            resultContainer.style.opacity = '1';
        })
        .catch(error => {
            console.error('Error:', error);
            resultContainer.style.opacity = '1';
        });
    }

    // 1. Sự kiện khi click vào các Tab lọc (ALL, NEWEST, v.v.)
    filterItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Nếu click vào container của Authors thì không reset filter ở đây
            if (this.classList.contains('iddi-author-dropdown-container')) return;
            
            e.preventDefault();
            
            // Xóa active ở các tab khác
            filterItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // Reset author nếu người dùng chọn tab lọc chung (tùy chọn UI)
            // Nếu bạn muốn lọc song song (vừa Popular vừa Author) thì comment dòng dưới
            // selectedAuthorId = 0; 
            // document.querySelectorAll('.author-item').forEach(ai => ai.classList.remove('active'));

            fetchCourses();
        });
    });

    // 2. Sự kiện khi click chọn Author trong Dropdown
    authorItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Ngăn chặn sự kiện nổi bọt lên cha

            // Update UI cho danh sách author
            authorItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            // Lấy ID từ attribute data-author-id
            selectedAuthorId = this.dataset.authorId;

            // Highlight nút AUTHORS chính
            const authorBtn = document.querySelector('.iddi-author-dropdown-container .filter-link');
            if (authorBtn) authorBtn.style.color = '#ff6600';

            fetchCourses();
        });
    });

    // 3. Sự kiện tìm kiếm (Debounce 500ms)
    let typingTimer;
    searchInput.addEventListener('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(fetchCourses, 500);
    });

    // 4. Sự kiện Submit form
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        fetchCourses();
    });
});