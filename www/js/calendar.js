document.addEventListener("DOMContentLoaded", function () {
    const calendar = document.querySelector(".mainCal .calendar");
    const dateSpan = calendar.querySelector(".date");
    const prevBtn = calendar.querySelector(".year button:first-child");
    const nextBtn = calendar.querySelector(".year button:last-child");
    const tableBody = calendar.querySelector(".tableCal tbody");

    const today = new Date();
    let currentYear = today.getFullYear();
    let currentMonth = today.getMonth() + 1;

    // 휴관일 리스트 (예제)
    const holidays = ["2024-09-01", "2024-09-02", "2024-09-08", "2024-09-09"];

    // 달력 업데이트 함수
    function updateCalendar(year, month) {
        dateSpan.textContent = `${year}.${month.toString().padStart(2, "0")}`;
        tableBody.innerHTML = ""; // 기존 데이터 초기화

        const firstDay = new Date(year, month - 1, 1).getDay();
        const lastDate = new Date(year, month, 0).getDate();
        let date = 1;
        let row = document.createElement("tr");

        // 빈 칸 추가
        for (let i = 0; i < firstDay; i++) {
            row.appendChild(document.createElement("td"));
        }

        // 날짜 추가
        for (let i = firstDay; i < 7; i++) {
            row.appendChild(createDateCell(year, month, date++));
        }
        tableBody.appendChild(row);

        while (date <= lastDate) {
            row = document.createElement("tr");
            for (let i = 0; i < 7 && date <= lastDate; i++) {
                row.appendChild(createDateCell(year, month, date++));
            }
            tableBody.appendChild(row);
        }
    }

    // 날짜 셀 생성 함수
    function createDateCell(year, month, day) {
        const cell = document.createElement("td");
        const span = document.createElement("span");
        span.textContent = day;
        cell.appendChild(span);

        // 오늘 날짜 강조
        const today = new Date();
        if (year === today.getFullYear() && month === today.getMonth() + 1 && day === today.getDate()) {
            cell.classList.add("today");
        }

        // 휴관일 적용
        const dateString = `${year}-${month.toString().padStart(2, "0")}-${day.toString().padStart(2, "0")}`;
        if (holidays.includes(dateString)) {
            cell.classList.add("holiday");
        }

        // 휴관요일 적용
        const date = new Date(year, month - 1, day);
        const dayOfWeek = date.getDay();
        if (dayOfWeek === 0 || dayOfWeek === 1) { // 0: Sunday, 1: Monday
            cell.classList.add("holiday");
        }

        return cell;
    }

    // 버튼 이벤트
    prevBtn.addEventListener("click", function () {
        if (--currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
        updateCalendar(currentYear, currentMonth);
    });

    nextBtn.addEventListener("click", function () {
        if (++currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        }
        updateCalendar(currentYear, currentMonth);
    });

    // 초기 로드
    updateCalendar(currentYear, currentMonth);
});
