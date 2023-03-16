// phpcs:disable PEAR.Functions.FunctionCallSignature
// phpcs:disable WordPress

import React from 'react'
import { render } from 'react-dom'
import App from './App'

const root = document.getElementById('react-app')
root && render(<App/>, root)
