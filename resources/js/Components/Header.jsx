import React from 'react'

const Header = ({title}) => {
  return (
    <div className="flex flex-col justify-center items-center">

        <header className="hidden sm:flex">
            <h1 className="mt-10 text-base sm:text-lg md:text-xl lg:text-2xl">{title}</h1>
        </header>

    </div>
  )
}

export default Header
