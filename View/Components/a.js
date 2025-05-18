for (let appt of existingAppointments) {
    const apptStart = new Date(appt.start);
    const apptEnd = new Date(appt.end);
    const currentStart = new Date(startInput);
    const currentEnd = new Date(endInput);

    // Trường hợp 1: Trùng thời gian
    if ((currentStart < apptEnd) && (currentEnd > apptStart)) {
        if (confirm("Bạn đã có cuộc hẹn vào thời gian này. Bạn có muốn thay thế cuộc hẹn trước không?")) {
            // Gửi form như bình thường và xử lý ở server
            return true;
        } else {
            return false;
        }
    }

    // Trường hợp 2: Trùng tên và thời lượng
    const durationCurrent = (currentEnd - currentStart) / (1000 * 60); // phút
    const durationAppt = (new Date(appt.end) - new Date(appt.start)) / (1000 * 60);
    if (appt.name === name && durationCurrent === durationAppt) {
        if (confirm("Đã tồn tại một cuộc họp nhóm giống như vậy. Bạn có muốn tham gia không?")) {
            // Gửi form với thông tin đặc biệt (ví dụ: thêm tham số `join_group=true`)
            const form = document.querySelector('form');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'join_group';
            hiddenInput.value = 'true';
            form.appendChild(hiddenInput);
            return true;
        } else {
            return false;
        }
    }
}
