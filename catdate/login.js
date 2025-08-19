document.getElementById('loginForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const formData = new FormData(this);

  // 前端驗證
  const username = formData.get('username');
  const password = formData.get('password');
  const msg = document.getElementById('message');
  
  msg.textContent = '';
  msg.style.color = '';

  if (!username || !password) {
    msg.textContent = '❌ 請輸入帳號和密碼';
    msg.style.color = 'red';
    return;
  }

  try {
    const res = await fetch('login.php', {
      method: 'POST',
      body: formData
    });

    const data = await res.json();
    
    if (data.status === 1) {
      msg.textContent = '✅ 登入成功！正在跳轉...';
      msg.style.color = 'green';
      // 延遲跳轉到會員中心或首頁
      setTimeout(() => {
        window.location.href = "dashboard.html";
      }, 1500);
    } else if (data.status === 0) {
      msg.textContent = '❌ ' + (data.error || '登入失敗');
      msg.style.color = 'red';
    } else {
      msg.textContent = '❌ ' + (data.error || '系統錯誤');
      msg.style.color = 'red';
    }
  } catch (error) {
    msg.textContent = '❌ 網路錯誤，請稍後再試';
    msg.style.color = 'red';
  }
});
