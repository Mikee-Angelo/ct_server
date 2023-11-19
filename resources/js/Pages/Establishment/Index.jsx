import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from '@inertiajs/react';
import { Button, useDisclosure } from '@chakra-ui/react';
import { AddIcon } from '@chakra-ui/icons';
import Create from "./Create";
import DataTable from 'react-data-table-component';

export default function Index({ auth, establishments }) {
    const columns = [
        {
            name: 'Code',
            selector: row => row.establishment_code,
        },
        {
            name: 'Name',
            selector: row => row.establishment_name,
        },
        {
            name: 'Email',
            selector: row => row.email_address,
        },
        {
            name: 'Contact Person',
            selector: row => row.first_name + ' ' + row.last_name,
        },
        {
            name: 'Contact Number',
            selector: row => '0' + row.contact_number
        },
        {
            name: 'Address',
            selector: row => row.address
        },
        {
            name: 'Baranggay',
            selector: row => row.baranggay
        },
    ];

    const conditionalRowStyles = [{
        when: row => row.status == 'Infected',
        style: {
            backgroundColor: 'rgb(239, 68, 68)',
            color: 'white',
            '&:hover': {
              cursor: 'pointer',
            },
          },
    }];

    const { isOpen, onOpen, onClose } = useDisclosure();

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Establishments</h2>}
        >
            <Head title="Establishments" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <Button leftIcon={<AddIcon />} colorScheme='green' mb={4} onClick={onOpen}>Add New</Button>

                    <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <DataTable
                            columns={columns}
                            data={establishments.data}
                            pagination
                            conditionalRowStyles={conditionalRowStyles}
                        />
                    </div>
                </div>
            </div>

            <Create isOpen={isOpen} onClose={onClose} />
        </AuthenticatedLayout>
    );
}
