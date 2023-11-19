import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from '@inertiajs/react';
import { Button, useDisclosure } from '@chakra-ui/react';
import { AddIcon } from '@chakra-ui/icons';
import Create from "./Create";

export default function Index({ auth }) {
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

                    </div>
                </div>
            </div>

            <Create isOpen={isOpen} onClose={onClose}/>
        </AuthenticatedLayout>
    );
}
