document.addEventListener("DOMContentLoaded", function () {
    const calendar = document.querySelector(".mainCal .calendar");
    const dateSpan = calendar.querySelector(".date");
    const prevBtn = calendar.querySelector(".year button:first-child");
    const nextBtn = calendar.querySelector(".year button:last-child");
    const tableBody = calendar.querySelector(".tableCal tbody");

    const today = new Date();
    let currentYear = today.getFullYear();
    let currentMonth = today.getMonth() + 1;

    // Parse JSON strings
    const holidayWeekdays = holidayWeekdaysJson;  // JSON.parse() 제거
    const specificHolidayDates = specificHolidayDatesJson.filter(date => date !== "-0001-11-30");

    // Log JSON strings before parsing
    console.log("specificHolidayDatesJson:", specificHolidayDatesJson);
    console.log("specificHolidayDates:", specificHolidayDates);

    // Helper function to check if a date is a holiday
    function isHoliday(date) {
        // 로컬 시간 기준으로 날짜 문자열 생성
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const dateString = `${year}-${month}-${day}`;

        if (specificHolidayDates.includes(dateString)) {
            return true;
        }

        const dayNames = ['일', '월', '화', '수', '목', '금', '토'];
        const dayName = dayNames[date.getDay()];
        return holidayWeekdays.includes(dayName);
    }

    // Update calendar function
    function updateCalendar(year, month) {
        dateSpan.textContent = `${year}.${month.toString().padStart(2, "0")}`;
        tableBody.innerHTML = "";

        const firstDay = new Date(year, month - 1, 1).getDay();
        const lastDate = new Date(year, month, 0).getDate();
        let date = 1;
        let row = document.createElement("tr");

        for (let i = 0; i < firstDay; i++) {
            row.appendChild(document.createElement("td"));
        }

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

    // Create date cell function
    function createDateCell(year, month, day) {
        const cell = document.createElement("td");
        const span = document.createElement("span");
        span.textContent = day;
        cell.appendChild(span);

        const currentDate = new Date();
        if (year === currentDate.getFullYear() && month === currentDate.getMonth() + 1 && day === currentDate.getDate()) {
            cell.classList.add("today");
        }

        const date = new Date(year, month - 1, day);
        if (isHoliday(date)) {
            cell.classList.add("holiday");
        }

        return cell;
    }

    // Button events
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

    // Initial load
    updateCalendar(currentYear, currentMonth);
});