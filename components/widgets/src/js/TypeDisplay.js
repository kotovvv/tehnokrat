import React, { memo } from 'react'
import { createPortal } from 'react-dom'


const TypeDisplay = memo(({ inDisplay, switchInDisplay, container }) => {
  return createPortal(<div className="type-display">
    <input id="bloks" type="radio" name="typedisplay" value="bloks" checked={inDisplay === 'bloks'} onChange={switchInDisplay} />
    <label className="bloks" htmlFor="bloks" ><svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
      <rect width="9" height="9" fill="white"></rect>
      <rect x="14" width="9" height="9" fill="white"></rect>
      <rect y="14" width="9" height="9" fill="white"></rect>
      <path d="M14 14H23V23H14V14Z" fill="white"></path>
    </svg></label>
    <input id="lines" type="radio" name="typedisplay" value="lines" checked={inDisplay === 'lines'} onChange={switchInDisplay} />
    <label className="lines" htmlFor="lines" ><svg width="27" height="23" viewBox="0 0 27 23" fill="none" xmlns="http://www.w3.org/2000/svg">
      <rect width="9" height="9" fill="white"></rect>
      <rect x="18" y="14" width="9" height="9" fill="white"></rect>
      <line x1="13" y1="1.5" x2="27" y2="1.5" stroke="white"></line>
      <line x1="13" y1="5.5" x2="27" y2="5.5" stroke="white"></line>
      <line y1="15.5" x2="14" y2="15.5" stroke="white"></line>
      <line y1="20.5" x2="14" y2="20.5" stroke="white"></line>
    </svg></label>
  </div >,
    container
  )
})

export default TypeDisplay