import React from 'react'

export default ({ part, partprivat }) => {

  if (part > 0 || partprivat > 0) {
    return <><div className='part'><p>{tehnokrat.strings['Pay part']}</p>
      {part > 0 ? <span><div className="i"><p className="h4">«Покупка частинами» від monobank</p><span>Для оформлення необхідно:</span><ul><li>1. Бути клієнтом monobank</li><li>2. Мати смартфон з додатком monobank</li><li>3. Перевірити доступний ліміт на розстрочку</li><li>4. Мати на карті суму для першого платежу</li></ul><a href="https://tehnokrat.ua/ua/shipping-and-payment/" target="_blank">Детальніше</a></div><img src='/wp-content/themes/tehnokrat/img/Mono.png' alt='monobank pay part' /></span> : <b></b>}{partprivat > 0 ? <span><div className="i"><p className="h4">«Миттєва розстрочка» від ПриватБанк</p><span>Для оформлення необхідно:</span><ul><li>1. Мати картку «Універсальна»</li><li>2. <a href="https://paypartslimit.privatbank.ua/pp-limit/" target="_blank">Дізнатись свій ліміт</a> «Миттєва розстрочка»</li><li>3. Мати на карті суму для першого платежу</li></ul><a href="https://tehnokrat.ua/ua/shipping-and-payment/" target="_blank">Детальніше</a></div><img src="/wp-content/themes/tehnokrat/img/Privat.png" alt="privatbank pay part" /></span> : <b></b>}
    </div></>
  }
  return <></>
}
