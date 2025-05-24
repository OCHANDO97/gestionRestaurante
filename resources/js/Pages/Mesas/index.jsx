import React from 'react'
import { Link  } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import MobileHeader from "@/Components/MobileHeader";
import Header from "@/Components/Header";

const index = ({tables}) => {
  return (
    <>
        <Navbar />
        <MobileHeader title="Comandas - Mesas" backRoute={route('dashboard')} />
        <Header title="Comandas - Mesas" />

        <div className='flex flex-wrap gap-8 justify-center mt-10'> 
        
            {tables.map((tab) => (
                <Link key={tab.tab_id} href={route('mesas.show',{ mesa: tab.tab_id })}>
                    <article className='bg-green-500 box-border h-20 w-44 p-4 text-center '>
                        <h1 key={tab.tab_id}> {tab.tab_name} </h1>
                        {tab.tab_state == 0 ? (
                            <h2>Libre</h2> 
                        ) : (
                            <h2>Ocupada</h2> 
                        )}
                    </article>
                
                </Link>
            ) )}
        </div>
    </>
  )
}

export default index