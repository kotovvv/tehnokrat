import React, { memo } from 'react'
import { createPortal } from 'react-dom'
const Sort = memo(({ inSort, switchInSort, container }) => {
  return createPortal(<div className="sorting">
    <span>
      <svg className="icon-sort" width="25" height="25" viewBox="0 0 32 32" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path d="M14.666 13.3333H23.9993" stroke="#343434" strokeWidth="1.5" strokeLinecap="round"
          strokeLinejoin="round"></path>
        <path d="M14.666 18.6667H21.3327" stroke="#343434" strokeWidth="1.5" strokeLinecap="round"
          strokeLinejoin="round"></path>
        <path d="M14.666 24H18.666" stroke="#343434" strokeWidth="1.5" strokeLinecap="round"
          strokeLinejoin="round"></path>
        <path d="M14.666 8H27.9993" stroke="#343434" strokeWidth="1.5" strokeLinecap="round"
          strokeLinejoin="round"></path>
        <path
          d="M9.33333 25.0833C8.80904 25.6733 7.41357 28 6.66667 28M6.66667 28C5.91976 28 4.52429 25.6733 4 25.0833M6.66667 28V20"
          stroke="#343434" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path>
        <path
          d="M4 6.91667C4.52429 6.32668 5.91976 4 6.66667 4M6.66667 4C7.41357 4 8.80904 6.32668 9.33333 6.91667M6.66667 4V12"
          stroke="#343434" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path>
      </svg>
      {tehnokrat.strings['Sort']}
      <svg className="icon-arrow" width="12" height="7" viewBox="0 0 12 7" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M1 1L5.41075 5.56844C5.6885 5.85612 5.82742 6 6 6C6.17258 6 6.3115 5.85612 6.58925 5.56844L11 1"
          stroke="#343434" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path>
      </svg>
    </span>
    <ul>
      <input id="upcost" type="radio" name="sortcost" value="upcost" checked={inSort === 'upcost'} onChange={switchInSort} />
      <label htmlFor="upcost" >{tehnokrat.strings['ASC']}</label>
      <input id="downcost" type="radio" name="sortcost" value="downcost" checked={inSort === 'downcost'} onChange={switchInSort} />
      <label htmlFor="downcost" >{tehnokrat.strings['DESC']}</label>
    </ul>
  </div>,
    container)
}
)

export default Sort