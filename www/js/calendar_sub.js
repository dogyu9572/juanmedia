document.addEventListener("DOMContentLoaded", function () {
    const currentDate = new Date();
    let selectedYear = currentDate.getFullYear();
    let selectedMonth = currentDate.getMonth();

    const calendarTitle = document.querySelector(".year .num");
    const prevButton = document.querySelector(".year a:first-child");
    const nextButton = document.querySelector(".year a:last-child");
    const calendarContainer = document.querySelector(".contScroll ul");

    const formatMonth = (month) => String(month + 1).padStart(2, '0');

    const calendarConfig = {
        unavailableWeekdays: holidayWeekdaysJson.map(day => {
            const weekdayMap = { '일': 0, '월': 1, '화': 2, '수': 3, '목': 4, '금': 5, '토': 6 };
            return weekdayMap[day];
        }),
        specificHolidayDates: specificHolidayDatesJson
    };

    function checkDateAvailability(date) {
        const year = date.getFullYear();
        const month = date.getMonth() + 1;
        const day = date.getDate();
        const weekday = date.getDay();

        // 특정 날짜 휴일 체크
        const specificDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        if (calendarConfig.specificHolidayDates.includes(specificDate)) {
            return 'disable';
        }

        // 요일별 휴일 체크
        if (calendarConfig.unavailableWeekdays.includes(weekday)) {
            return 'disable';
        }

        return 'able';
    }

    function renderCalendar(year, month) {
        calendarTitle.textContent = `${year}.${formatMonth(month)}`;
        calendarContainer.innerHTML = '';

        const firstDate = new Date(year, month, 1);
        const lastDate = new Date(year, month + 1, 0);
        const weekdayLabels = ['일', '월', '화', '수', '목', '금', '토'];

        for (let date = 1; date <= lastDate.getDate(); date++) {
            const currentDate = new Date(year, month, date);
            const dayOfWeek = currentDate.getDay();
            const weekLabel = weekdayLabels[dayOfWeek];

            const li = document.createElement('li');
            const a = document.createElement('a');

            const availabilityClass = checkDateAvailability(currentDate);
            if (availabilityClass) {
                a.classList.add(availabilityClass);
            }

            a.innerHTML = `
                <span class="week">${weekLabel}</span>
                <span class="day">${date}</span>
            `;

            a.addEventListener('click', function(e) {
                e.preventDefault();
                if (a.classList.contains('able')) {
                    const selectedDate = `${year}-${formatMonth(month)}-${String(date).padStart(2, '0')}`;
                    document.querySelector('#st1').value = selectedDate;
                }
            });

            li.appendChild(a);
            calendarContainer.appendChild(li);
        }
    }

    // 이전/다음 달 이동 이벤트 리스너 (기존과 동일)
    prevButton.addEventListener("click", function(e) {
        e.preventDefault();
        if (selectedMonth === 0) {
            selectedYear--;
            selectedMonth = 11;
        } else {
            selectedMonth--;
        }
        renderCalendar(selectedYear, selectedMonth);
    });

    nextButton.addEventListener("click", function(e) {
        e.preventDefault();
        if (selectedMonth === 11) {
            selectedYear++;
            selectedMonth = 0;
        } else {
            selectedMonth++;
        }
        renderCalendar(selectedYear, selectedMonth);
    });

    // 초기 달력 렌더링
    renderCalendar(selectedYear, selectedMonth);
});