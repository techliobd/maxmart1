<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $backups = [];
        
        if (Storage::disk('public')->exists('backups')) {
            $files = Storage::disk('public')->files('backups');
            
            foreach ($files as $file) {
                $backups[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => Storage::disk('public')->size($file),
                    'created_at' => Storage::disk('public')->lastModified($file),
                ];
            }
        }

        // Sort by created_at descending
        usort($backups, function ($a, $b) {
            return $b['created_at'] - $a['created_at'];
        });

        return view('admin.backups.index', compact('backups'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'type' => 'required|in:database,full',
        ]);

        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            
            if ($request->type === 'database') {
                // Export database
                $tables = DB::select('SHOW TABLES');
                $dbName = env('DB_DATABASE');
                $tableKey = "Tables_in_{$dbName}";
                
                $sql = "-- MaxMart Database Backup\n-- Generated: " . now()->format('Y-m-d H:i:s') . "\n\n";
                
                foreach ($tables as $table) {
                    $tableName = $table->$tableKey;
                    
                    // Get table structure
                    $structure = DB::select("SHOW CREATE TABLE `{$tableName}`");
                    $sql .= "-- Table structure for {$tableName}\n";
                    $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                    $sql .= $structure[0]->{'Create Table'} . ";\n\n";
                    
                    // Get table data
                    $rows = DB::table($tableName)->get();
                    if ($rows->count() > 0) {
                        $sql .= "-- Data for {$tableName}\n";
                        foreach ($rows as $row) {
                            $values = array_map(function ($value) {
                                if ($value === null) {
                                    return 'NULL';
                                } elseif (is_numeric($value)) {
                                    return $value;
                                } else {
                                    return "'" . addslashes($value) . "'";
                                }
                            }, (array) $row);
                            
                            $sql .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
                        }
                        $sql .= "\n";
                    }
                }
                
                Storage::disk('public')->put("backups/{$filename}", $sql);
                
                return back()->with('success', 'Database backup created successfully: ' . $filename);
            }
            
            return back()->withErrors(['error' => 'Full backup not implemented yet.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Backup failed: ' . $e->getMessage()]);
        }
    }

    public function download($filename)
    {
        if (!Storage::disk('public')->exists("backups/{$filename}")) {
            return back()->withErrors(['error' => 'Backup file not found.']);
        }

        return Storage::disk('public')->download("backups/{$filename}");
    }

    public function destroy($filename)
    {
        if (!Storage::disk('public')->exists("backups/{$filename}")) {
            return back()->withErrors(['error' => 'Backup file not found.']);
        }

        Storage::disk('public')->delete("backups/{$filename}");

        return back()->with('success', 'Backup deleted successfully.');
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,zip,gz',
        ]);

        try {
            $file = $request->file('backup_file');
            $content = file_get_contents($file->getRealPath());
            
            // Split SQL file into individual statements
            $statements = array_filter(
                array_map('trim', explode(';', $content)),
                function ($statement) {
                    return !empty($statement) && 
                           !str_starts_with($statement, '--') && 
                           !str_starts_with($statement, '/*');
                }
            );

            DB::beginTransaction();
            
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    DB::statement($statement);
                }
            }
            
            DB::commit();

            return back()->with('success', 'Database restored successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Restore failed: ' . $e->getMessage()]);
        }
    }
}
