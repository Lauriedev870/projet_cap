<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Stockage\Models\File;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Création des documents officiels du CAP...');

        // ID de l'utilisateur administrateur par défaut
        $userId = 1;

        $documents = [
            [
                'original_name' => 'Règlement Intérieur du CAP.pdf',
                'description' => 'Document officiel définissant les règles de fonctionnement et de vie au sein de l\'établissement',
                'document_categorie' => 'legal',
                'extension' => 'pdf',
                'size' => 2516582,
                'path' => 'documents/reglement-interieur.pdf',
                'date_publication' => '2024-01-15',
            ],
            [
                'original_name' => 'Brochure des Formations 2024-2025.pdf',
                'description' => 'Présentation complète de tous nos programmes de formation avec les conditions d\'admission',
                'document_categorie' => 'pedagogique',
                'extension' => 'pdf',
                'size' => 5347737,
                'path' => 'documents/brochure-formations.pdf',
                'date_publication' => '2024-02-01',
            ],
            [
                'original_name' => 'Organigramme de l\'Établissement.pdf',
                'description' => 'Structure organisationnelle et hiérarchique du Centre Autonome de Perfectionnement',
                'document_categorie' => 'organisation',
                'extension' => 'pdf',
                'size' => 1258291,
                'path' => 'documents/organigramme.pdf',
                'date_publication' => '2024-01-20',
            ],
            [
                'original_name' => 'Calendrier Académique 2024-2025.pdf',
                'description' => 'Dates importantes de l\'année universitaire : rentrée, examens, vacances',
                'document_categorie' => 'pedagogique',
                'extension' => 'pdf',
                'size' => 838860,
                'path' => 'documents/calendrier-academique.pdf',
                'date_publication' => '2024-03-01',
            ],
            [
                'original_name' => 'Guide de l\'Étudiant.pdf',
                'description' => 'Manuel complet avec toutes les informations pratiques pour réussir sa scolarité',
                'document_categorie' => 'pedagogique',
                'extension' => 'pdf',
                'size' => 3879731,
                'path' => 'documents/guide-etudiant.pdf',
                'date_publication' => '2024-02-15',
            ],
            [
                'original_name' => 'Statuts de l\'Association.pdf',
                'description' => 'Document fondateur définissant les statuts juridiques du CAP',
                'document_categorie' => 'legal',
                'extension' => 'pdf',
                'size' => 1572864,
                'path' => 'documents/statuts-association.pdf',
                'date_publication' => '2023-12-10',
            ],
            [
                'original_name' => 'Formulaire de Demande d\'Information.doc',
                'description' => 'Formulaire à remplir pour obtenir des informations complémentaires sur nos formations',
                'document_categorie' => 'administratif',
                'extension' => 'doc',
                'size' => 314572,
                'path' => 'documents/formulaire-information.doc',
                'date_publication' => '2024-03-10',
            ],
            [
                'original_name' => 'Rapport d\'Activité 2023.pdf',
                'description' => 'Bilan complet des activités et réalisations de l\'établissement pour l\'année 2023',
                'document_categorie' => 'organisation',
                'extension' => 'pdf',
                'size' => 4404019,
                'path' => 'documents/rapport-activite-2023.pdf',
                'date_publication' => '2024-01-30',
            ],
        ];

        $created = 0;
        foreach ($documents as $docData) {
            try {
                $mimeType = $docData['extension'] === 'pdf' ? 'application/pdf' : 'application/msword';
                
                File::create([
                    'user_id' => $userId,
                    'name' => Str::uuid() . '.' . $docData['extension'],
                    'original_name' => $docData['original_name'],
                    'description' => $docData['description'],
                    'path' => $docData['path'],
                    'disk' => 'public',
                    'visibility' => 'public',
                    'collection' => 'documents_officiels',
                    'size' => $docData['size'],
                    'mime_type' => $mimeType,
                    'extension' => $docData['extension'],
                    'document_categorie' => $docData['document_categorie'],
                    'is_official_document' => true,
                    'date_publication' => Carbon::parse($docData['date_publication']),
                ]);
                $created++;
            } catch (\Exception $e) {
                $this->command->warn("⚠️  Erreur pour '{$docData['original_name']}': {$e->getMessage()}");
            }
        }

        $this->command->info('');
        $this->command->info("🎉 $created documents officiels créés avec succès !");
        $this->command->info('');
        $this->command->info('📊 Résumé par catégorie :');
        $this->command->info('   → Pédagogique : 3 documents');
        $this->command->info('   → Juridique : 2 documents');
        $this->command->info('   → Organisation : 2 documents');
        $this->command->info('   → Administratif : 1 document');
        $this->command->info('');
        $this->command->info('🔗 Testez l\'API: curl http://127.0.0.1:8000/api/documents');
    }
}
