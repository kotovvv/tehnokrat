import React from 'react'

export default ({ part, partprivat }) => {

  if (part > 0 || partprivat > 0) {
    return <><div className='part'><p>{tehnokrat.strings['Pay part']}</p>
      {part > 0 ? <span><i>{tehnokrat.strings['Mono Pay part']}</i><img src='/wp-content/themes/tehnokrat/img/Mono.png' alt='monobank pay part' /></span> : <b></b>}
      {partprivat > 0 ? <span><i>{tehnokrat.strings['Privat Pay part']}</i><img src="/wp-content/themes/tehnokrat/img/Privat.png" alt="privatbank pay part" /></span> : <b></b>}
    </div></>
  }
  return
}
